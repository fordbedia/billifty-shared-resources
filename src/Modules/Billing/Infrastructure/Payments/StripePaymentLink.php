<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments;

use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;
use BilliftySDK\SharedResources\Modules\Billing\Application\Ports\InvoicePaymentLinkGateway;
use BilliftySDK\SharedResources\Modules\Billing\Application\Ports\StripeInvoicePaymentLink;
use BilliftySDK\SharedResources\Modules\Billing\DTO\CreateInvoicePaymentLinkData;
use BilliftySDK\SharedResources\Modules\Billing\DTO\PaymentLinkResult;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents\InvoiceRepository;
use Stripe\StripeClient;

class StripePaymentLink implements InvoicePaymentLinkGateway
{
	public function __construct(
		protected InvoiceRepository $invoiceRepo,
		protected StripeClient      $stripe,
	)
	{
	}

	public function provider(): PaymentProvider
	{
		return PaymentProvider::STRIPE;
	}

	/**
	 * @param CreateInvoicePaymentLinkData $data
	 * @return PaymentLinkResult
	 */
	public function create(CreateInvoicePaymentLinkData $data): PaymentLinkResult
	{
		$invoice = $this->invoiceRepo->getModel()->whereHas('paymentLink', function ($q) use ($data) {
			$q->where('token', $data->token);
		})->first();

		$stripeAcctId = $invoice?->businessProfile?->paymentInformation?->stripe_account_id;

		if (!$stripeAcctId) {
			abort(404, 'Stripe account` not found');
		}

		$currencyCode = strtolower($invoice->currency?->code ?? 'usd');

		$lineItems = $invoice->items->map(function ($item) use ($currencyCode, $invoice) {
			$quantity = (int)$item->quantity;

			$unitAmountCents = isset($item->unit_price_cents)
				? (int)$item->unit_price_cents
				: (int)round(((float)$item->unit_price) * 100);

			return [
				'quantity' => max($quantity, 1),
				'price_data' => [
					'currency' => $currencyCode,
					'unit_amount' => $unitAmountCents,
					'product_data' => [
						'name' => str($item->description ?: 'Invoice item')->limit(60)->toString(),
						'description' => 'Invoice #' . $invoice->invoice_number,
					],
				],
			];
		})->values()->all();

		$session = $this->stripe->checkout->sessions->create([
			'mode' => 'payment',
			'customer_email' => $invoice?->client?->email,
			'success_url' => $data->successUrl,
			'cancel_url' => $data->cancelUrl,
			'line_items' => $lineItems,
			'metadata' => [
				'invoice_id' => $invoice->id,
				'business_profile_id' => $data->businessProfileId,
			],
		], [
			'stripe_account' => $stripeAcctId,
		]);

		return new PaymentLinkResult(
			provider: PaymentProvider::STRIPE,
			url: $session->url,
			externalReference: $session->id,
			metadata: [
				'checkout_session_id' => $session->id,
			],
		);
	}
}
