<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments;

use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;
use BilliftySDK\SharedResources\Modules\Billing\Application\Ports\InvoicePaymentLinkGateway;
use BilliftySDK\SharedResources\Modules\Billing\DTO\CreateInvoicePaymentLinkData;
use BilliftySDK\SharedResources\Modules\Billing\DTO\PaymentLinkResult;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents\InvoiceRepository;
use RuntimeException;

class PayPalPaymentLinkAdapter implements InvoicePaymentLinkGateway
{
	protected PayPalGateway $paypal;

	public function __construct(
		protected InvoiceRepository $invoiceRepo,
		?PayPalGateway $paypal = null
	)
	{
		$this->paypal = $paypal ?? new PayPalGateway;
	}

	public function provider(): PaymentProvider
	{
		return PaymentProvider::PAYPAL;
	}

	public function create(CreateInvoicePaymentLinkData $data): PaymentLinkResult
	{
		$invoice = $this->invoiceRepo->getModel()
			->with(['businessProfile.paymentInformations', 'paymentLink', 'items', 'client', 'currency'])
			->whereHas('paymentLink', function ($q) use ($data) {
				$q->where('token', $data->token);
			})
			->first();

		if (!$invoice) {
			throw new RuntimeException('Invoice payment link not found.');
		}

		$url = $this->paypal->createPaymentLink($invoice, $data);
		$invoice->loadMissing('paymentLink');

		return new PaymentLinkResult(
			provider: PaymentProvider::PAYPAL,
			url: $url,
			externalReference: $invoice->paymentLink?->paypal_order_id ? (string)$invoice->paymentLink->paypal_order_id : null,
			metadata: [
				'paypal_order_id' => $invoice->paymentLink?->paypal_order_id,
			],
		);
	}
}
