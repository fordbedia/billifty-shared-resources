<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;
use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentLink;
use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentRecord;
use BilliftySDK\SharedResources\Modules\Billing\Services\Billing\InvoicePaymentLinkService;
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

class InvoicePaymentController extends Controller
{
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
		return $paymentLinkRepository->findByToken($request->token)
			->loadMissing(PaymentLink::relationships());
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

		// Check if payment link is revoked
		if($paymentLink->public_token_revoked_at && Carbon::now()->isAfter(Carbon::parse($paymentLink->public_token_revoked_at))) {
			abort(500, "Payment link is revoked");
		}

		if ($invoice->colorScheme) {
			$invoice->colorScheme->setRelation(
				'colors',
				$invoice->colorScheme->colors->keyBy('name')
			);
		}

		$payload = (new InvoiceResource($invoice))->response()->getData();

		return response()
			->view('billing::invoice.preview', [
				'invoiceModel' => $invoice,
				'invoice' => $payload,
				'category' => data_get($payload, 'template.category'),
				'colorScheme' => data_get($payload, 'colorScheme'),
				'renderContext' => 'public-html',
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

		if ($paymentLink->public_token_revoked_at && Carbon::now()->isAfter(Carbon::parse($paymentLink->public_token_revoked_at))) {
			abort(410, 'Payment link is revoked.');
		}

		$userId = $invoice->workspace?->user_id;

		if (! $userId) {
			abort(404, 'Invoice owner not found.');
		}

		$disk = Storage::disk($invoice->pdf_disk ?? 'public');
		$pdfExists = $invoice->pdf_path && $disk->exists($invoice->pdf_path);

		if ($invoice->pdf_status !== 'ready' || ! $pdfExists) {
			$invoice->forceFill([
				'pdf_status' => 'processing',
				'pdf_error' => null,
			])->save();

			try {
				['invoice' => $invoice] = $generateInvoicePdf($invoice);

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
}
