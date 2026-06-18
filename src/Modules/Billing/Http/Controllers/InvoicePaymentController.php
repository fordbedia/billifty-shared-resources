<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;
use BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments\Traits\Security\ValidatesInvoiceState;
use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentLink;
use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentRecord;
use BilliftySDK\SharedResources\Modules\Billing\Services\Billing\InvoicePaymentLinkService;
use BilliftySDK\SharedResources\Modules\Billing\Support\PlanPermission;
use BilliftySDK\SharedResources\Modules\Invoicing\Action\GenerateInvoicePdf;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\InvoiceController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources\InvoiceResource;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\PaymentLinkRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Throwable;
use function Symfony\Component\String\s;

class InvoicePaymentController extends Controller
{
	use ValidatesInvoiceState;

	public function getPaymentLink(
		Request                   $request,
		InvoicePaymentLinkService $paymentLinkService,
		string                    $token,
	)
	{
		$validated = $request->validate([
			'provider' => ['required', 'string'],
		]);

		$provider = PaymentProvider::from($validated['provider']);
		$paymentLink = PaymentLink::where('token', $token)->with('invoice')->first();

		if ($paymentLink?->invoice) {
			$this->validateInvoiceVoidState($paymentLink->invoice);
		}

		$result = $paymentLinkService->createForInvoice(
			token: $token,
			provider: $provider,
			businessProfileId: $request->user()->current_business_profile_id ?? null,
			successUrl: config('app.frontend_url') . "/app/invoices/{$token}/payment-success",
			cancelUrl: config('app.frontend_url') . "/app/invoices/{$token}/{$provider->value}/payment-cancelled",
		);

		return response()->json([
			'provider' => $result->provider->value,
			'url' => $result->url,
			'external_reference' => $result->externalReference,
			'metadata' => $result->metadata,
		]);
	}

	public function invoiceSuccessPayment(Request $request)
	{
		return PaymentRecord::whereToken($request->token)->first();
	}

	public function paymentLinkData(
		Request               $request,
		PaymentLinkRepository $paymentLinkRepository
	): PaymentLink
	{
		$paymentLink = $paymentLinkRepository->findByToken($request->token);

		if (! $paymentLink) {
			abort(404, 'Something went wrong. Payment link not found.');
		}

		return $paymentLink->loadMissing(PaymentLink::relationships());
	}

	public function preview(
		Request               $request,
		PaymentLinkRepository $paymentLinkRepository,
	)
	{
		$paymentLink = $paymentLinkRepository->findByToken($request->token)?->loadMissing(PaymentLink::relationships());

		if (! $paymentLink) {
			return view('billing::error.error-invoice-not-found');
		}

		$invoice = $paymentLink->invoice->loadMissing(Invoices::relationships());
		$invoice->setRelation('paymentLink', $paymentLink);

		// ----------------------------------------------------------------------------
		// 1. Check if payment link is revoked
		// 2. Check if invoices.status !== 'draft'
		// ----------------------------------------------------------------------------
		if($paymentLink->public_token_revoked_at && Carbon::now()->isAfter(Carbon::parse($paymentLink->public_token_revoked_at))) {
			return view('billing::error.error-invoice-not-found');
		}

		if ($invoice->status === 'draft') {
			return view('billing::error.error-invoice-not-found');
		}

		if ($invoice->colorScheme) {
			$invoice->colorScheme->setRelation(
				'colors',
				$invoice->colorScheme->colors->keyBy('name')
			);
		}

		$payload = (new InvoiceResource($invoice))->response()->getData();

		$user = $invoice->refresh()->workspace?->user;

		$capabilities = PlanPermission::attempt($user)->toArray();

		return response()
			->view('billing::invoice.preview', [
				'invoiceModel' => $invoice,
				'invoice' => $payload,
				'category' => data_get($payload, 'template.category'),
				'colorScheme' => data_get($payload, 'colorScheme'),
				'renderContext' => 'public-html',
				'capabilities' => $capabilities,
			])->header('X-Robots-Tag', 'noindex, nofollow');
	}

	public function downloadPdf(
		Request               $request,
		PaymentLinkRepository $paymentLinkRepository,
		InvoiceContracts      $invoiceRepository,
		GenerateInvoicePdf    $generateInvoicePdf,
		InvoiceController     $invoiceController,
	)
	{
		$paymentLink = $paymentLinkRepository->findByToken($request->token)?->loadMissing(PaymentLink::relationships());

		if (! $paymentLink || ! $paymentLink->invoice) {
			abort(404, 'Invoice not found.');
		}

		$invoice = $paymentLink->invoice->loadMissing(Invoices::relationships());
		$invoice->setRelation('paymentLink', $paymentLink);

		// Check if invoice is valid for download.
		$this->validateInvoiceDraftState($invoice);

		if ($paymentLink->public_token_revoked_at && Carbon::now()->isAfter(Carbon::parse($paymentLink->public_token_revoked_at))) {
			return view('billing::error.error-invoice-not-found');
		}

		$userId = $invoice->workspace?->user_id;

		if (! $userId) {
			abort(404, 'Invoice owner not found.');
		}

		$disk = Storage::disk($invoice->pdf_disk ?? 'public');
		$pdfExists = $invoice->pdf_path && $disk->exists($invoice->pdf_path);

		// ----------------------------------------------------------------------------
		// A ready PDF can still be stale. Public invoice PDFs embed the payment-link
		// token in the QR code and render the invoice total from total_cents. Reuse the
		// stored PDF only when both values match the current invoice/link; otherwise
		// regenerate so renewed links or edited totals do not serve outdated PDFs.
		// ----------------------------------------------------------------------------
		$invoiceMeta = $this->invoiceMeta($invoice);
		$pdfMatchesCurrentToken = data_get($invoiceMeta, 'pdf_payment_link_token') === $paymentLink->token;
		$pdfMatchesCurrentTotal = (int) data_get($invoiceMeta, 'pdf_total_cents', -1) === (int) ($invoice->total_cents ?? 0);

		if ($invoice->pdf_status !== 'ready' || ! $pdfExists || ! $pdfMatchesCurrentToken || ! $pdfMatchesCurrentTotal) {
			$invoice->forceFill([
				'pdf_status' => 'processing',
				'pdf_error' => null,
			])->save();

			try {
				['invoice' => $invoice] = $generateInvoicePdf($invoice, $paymentLink->token);

				$invoice->forceFill([
					'pdf_status' => 'ready',
					'pdf_generated_at' => now(),
					'pdf_error' => null,
				])->save();
			} catch (Throwable $exception) {
				$invoice->forceFill([
					'pdf_status' => 'failed',
					'pdf_error' => $exception->getMessage(),
				])->save();

				throw $exception;
			}
		}

		Auth::onceUsingId((int) $userId);

		return $invoiceController->download((int) $invoice->getKey(), $invoiceRepository);
	}

	private function invoiceMeta(Invoices $invoice): array
	{
		$meta = $invoice->meta;

		if (is_string($meta)) {
			return json_decode($meta, true) ?: [];
		}

		return is_array($meta) ? $meta : [];
	}
}
