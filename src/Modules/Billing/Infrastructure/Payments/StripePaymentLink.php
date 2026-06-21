<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments;

use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;
use BilliftySDK\SharedResources\Modules\Billing\Application\Ports\InvoicePaymentLinkGateway;
use BilliftySDK\SharedResources\Modules\Billing\Application\Ports\StripeInvoicePaymentLink;
use BilliftySDK\SharedResources\Modules\Billing\DTO\CreateInvoicePaymentLinkData;
use BilliftySDK\SharedResources\Modules\Billing\DTO\PaymentLinkResult;
use BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments\Traits\Security\ValidatePaymentLink;
use BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments\Traits\Security\ValidatesInvoiceState;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents\InvoiceRepository;
use Stripe\StripeClient;

class StripePaymentLink implements InvoicePaymentLinkGateway
{
	use ValidatesInvoiceState, ValidatePaymentLink;

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
		$invoice = $this->invoiceRepo->getModel()
			->with(['businessProfile.paymentInformations', 'paymentLink', 'items', 'client', 'currency'])
			->whereHas('paymentLink', function ($q) use ($data) {
				$q->where('token', $data->token);
			})
			->first();
		// ----------------------------------------------------------------------------
		// Validate Invoice
		// ----------------------------------------------------------------------------
		// Validate Invoice State
		$this->validateInvoiceState($invoice);
		// Validate Revoked Payment Link
		$this->validateRevokedPaymentLink($invoice);
		// Validate Expired Payment Link
		$this->validateExpiredPaymentLink($invoice);

		$stripePaymentInfo = $invoice?->businessProfile?->paymentInformations?->first(function ($paymentInfo) {
			$paymentMethod = $paymentInfo?->payment_method;
			$paymentMethod = $paymentMethod instanceof \BackedEnum ? $paymentMethod->value : (string) $paymentMethod;

			return $paymentMethod === 'stripe';
		});
		$stripeAcctId = $stripePaymentInfo?->stripe_account_id;

		if (!$stripeAcctId) {
			abort(404, 'Stripe account` not found');
		}

		$currencyCode = strtolower($invoice->currency?->code ?? 'usd');

		$lineItems = $this->buildLineItems($invoice, $currencyCode);

		$metadata = [
			'invoice_id' => (string)$invoice->id,
		];
		if ($data->businessProfileId !== null) {
			$metadata['business_profile_id'] = (string)$data->businessProfileId;
		}

		$sessionPayload = [
			'mode' => 'payment',
			'customer_email' => $invoice?->client?->email,
			'success_url' => $data->successUrl,
			'cancel_url' => $data->cancelUrl,
			'line_items' => $lineItems,
			'metadata' => $metadata,
			'payment_intent_data' => [
				'metadata' => $metadata,
			],
		];

		$discountCents = $this->checkoutDiscountCents($invoice, $lineItems);
		if ($discountCents > 0) {
			$coupon = $this->stripe->coupons->create([
				'amount_off' => $discountCents,
				'currency' => $currencyCode,
				'duration' => 'once',
				'name' => str($this->invoiceLabel($invoice) . ' discount')->limit(60)->toString(),
				'metadata' => $metadata,
			], [
				'stripe_account' => $stripeAcctId,
			]);

			$sessionPayload['discounts'] = [
				['coupon' => $coupon->id],
			];
		}

		$session = $this->stripe->checkout->sessions->create($sessionPayload, [
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

	protected function buildLineItems(Invoices $invoice, string $currencyCode): array
	{
		$invoiceLabel = $this->invoiceLabel($invoice);
		$lineItems = collect($invoice->items ?? [])
			->map(fn($item) => $this->buildInvoiceItemLineItem($item, $currencyCode, $invoiceLabel))
			->filter()
			->values()
			->all();

		$shippingCents = max((int)$invoice->shipping_cents, 0) + max((int)$invoice->shipping_tax_cents, 0);
		if ($shippingCents > 0) {
			$lineItems[] = $this->buildLineItem(
				$currencyCode,
				$shippingCents,
				'Shipping',
				$invoiceLabel
			);
		}

		$totalCents = $this->invoiceTotalCents($invoice);
		$lineItemsTotal = $this->lineItemsTotal($lineItems);

		if ($lineItemsTotal < $totalCents) {
			$lineItems[] = $this->buildLineItem(
				$currencyCode,
				$totalCents - $lineItemsTotal,
				'Invoice adjustment',
				$invoiceLabel
			);
		}

		if ($lineItems === []) {
			$lineItems[] = $this->buildLineItem(
				$currencyCode,
				$totalCents,
				$invoiceLabel,
				'Payment for ' . $invoiceLabel
			);
		}

		return $lineItems;
	}

	protected function buildInvoiceItemLineItem(mixed $item, string $currencyCode, string $invoiceLabel): ?array
	{
		$amountCents = $this->invoiceItemAmountCents($item);

		if ($amountCents <= 0) {
			return null;
		}

		$name = data_get($item, 'name') ?: data_get($item, 'description') ?: 'Invoice item';
		$quantity = data_get($item, 'quantity');
		$description = $invoiceLabel;

		if (is_numeric($quantity)) {
			$description .= '; Qty: ' . rtrim(rtrim((string)$quantity, '0'), '.');
		}

		return $this->buildLineItem(
			$currencyCode,
			$amountCents,
			$name,
			$description
		);
	}

	protected function buildLineItem(
		string $currencyCode,
		int $unitAmountCents,
		string $name,
		string $description,
	): array {
		return [
			'quantity' => 1,
			'price_data' => [
				'currency' => $currencyCode,
				'unit_amount' => max($unitAmountCents, 0),
				'product_data' => [
					'name' => str($name)->limit(60)->toString(),
					'description' => $description,
				],
			],
		];
	}

	protected function invoiceItemAmountCents(mixed $item): int
	{
		$lineTotalCents = data_get($item, 'line_total_cents');

		if (is_numeric($lineTotalCents) && (int)$lineTotalCents > 0) {
			return (int)$lineTotalCents;
		}

		$quantity = data_get($item, 'quantity', 1);
		$quantity = is_numeric($quantity) ? (float)$quantity : 1.0;

		$unitAmountCents = data_get($item, 'unit_price_cents');
		if (!is_numeric($unitAmountCents) || (int)$unitAmountCents <= 0) {
			$unitAmount = data_get($item, 'unit_price', 0);
			$unitAmountCents = is_numeric($unitAmount) ? (int)round((float)$unitAmount * 100) : 0;
		}

		return max((int)round($quantity * (int)$unitAmountCents), 0);
	}

	protected function checkoutDiscountCents(Invoices $invoice, array $lineItems): int
	{
		return max($this->lineItemsTotal($lineItems) - $this->invoiceTotalCents($invoice), 0);
	}

	protected function lineItemsTotal(array $lineItems): int
	{
		return collect($lineItems)->sum(function (array $lineItem): int {
			$quantity = max((int)($lineItem['quantity'] ?? 1), 1);
			$unitAmountCents = (int)data_get($lineItem, 'price_data.unit_amount', 0);

			return $quantity * $unitAmountCents;
		});
	}

	protected function invoiceTotalCents(Invoices $invoice): int
	{
		return max((int)$invoice->total_cents, 0);
	}

	protected function invoiceLabel(Invoices $invoice): string
	{
		return 'Invoice #' . ($invoice->invoice_number ?: $invoice->id);
	}
}
