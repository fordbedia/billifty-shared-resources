<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;
use BilliftySDK\SharedResources\Modules\Billing\Services\Billing\InvoicePaymentLinkService;
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
			successUrl: config('app.frontend_url') . "/invoices/{$token}/payment-success",
			cancelUrl: config('app.frontend_url') . "/invoices/{$token}/payment-cancelled",
		);

		return response()->json([
			'provider' => $result->provider->value,
			'url' => $result->url,
			'external_reference' => $result->externalReference,
			'metadata' => $result->metadata,
		]);
	}
}
