<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;
use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentLink;
use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentRecord;
use BilliftySDK\SharedResources\Modules\Billing\Services\Billing\InvoicePaymentLinkService;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources\InvoiceResource;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\PaymentLinkRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents\InvoiceRepository;
use http\Env\Url;
use Illuminate\Http\Request;

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

		$invoice = $paymentLink->invoice->loadMissing(Invoices::relationships());

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
				'renderContext' => 'html',
			])->header('X-Robots-Tag', 'noindex, nofollow');
	}
}
