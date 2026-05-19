<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments;

use BilliftySDK\SharedResources\Modules\Billing\DTO\CreateInvoicePaymentLinkData;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use RuntimeException;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;

class PayPalGateway
{
	public function createPaymentLink(
		Invoices $invoice,
		CreateInvoicePaymentLinkData $data
	): string {
		$invoice->loadMissing(['items', 'paymentLink', 'businessProfile.paypalInformation']);

		$businessProfile = $invoice->businessProfile?->paypalInformation;

		if (!$businessProfile?->paypal_merchant_id) {
			throw new RuntimeException('Business profile does not have a PayPal merchant ID.');
		}

		$provider = new PayPalClient;
		$provider->setApiCredentials(config('paypal'));
		$provider->getAccessToken();

		$currency = strtoupper($invoice->currency?->code ?? 'USD');

		$response = $provider->createOrder($this->buildOrderPayload(
			$invoice,
			$data,
			$currency,
			$businessProfile->paypal_merchant_id
		));

		if (!isset($response['id'])) {
			throw new RuntimeException('Failed to create PayPal order.');
		}

		$invoice->paymentLink?->update([
			'paypal_order_id' => $response['id'],
		]);

		foreach ($response['links'] ?? [] as $link) {
			if (($link['rel'] ?? null) === 'approve') {
				return $link['href'];
			}
		}

		throw new RuntimeException('PayPal approval link not found.');
	}

	protected function buildOrderPayload(
		Invoices $invoice,
		CreateInvoicePaymentLinkData $data,
		string $currency,
		string $merchantId
	): array {
		$items = $this->buildItems($invoice, $currency);
		$itemTotalCents = $this->itemsTotalCents($items);
		$taxTotalCents = max((int)$invoice->tax_cents, 0) + max((int)$invoice->shipping_tax_cents, 0);
		$shippingCents = max((int)$invoice->shipping_cents, 0);
		$totalCents = max((int)$invoice->total_cents, 0);

		$subtotalBeforeDiscountCents = $itemTotalCents + $taxTotalCents + $shippingCents;
		$discountCents = max($subtotalBeforeDiscountCents - $totalCents, 0);
		$reconciledTotalCents = $subtotalBeforeDiscountCents - $discountCents;

		if ($reconciledTotalCents < $totalCents) {
			$adjustmentCents = $totalCents - $reconciledTotalCents;
			$items[] = $this->buildItem(
				$currency,
				$adjustmentCents,
				'Invoice adjustment',
				$this->invoiceLabel($invoice)
			);
			$itemTotalCents += $adjustmentCents;
		}

		$amountBreakdown = [
			'tax_total' => [
				'currency_code' => $currency,
				'value' => $this->money($taxTotalCents),
			],
			'shipping' => [
				'currency_code' => $currency,
				'value' => $this->money($shippingCents),
			],
			'discount' => [
				'currency_code' => $currency,
				'value' => $this->money($discountCents),
			],
		];

		if ($items !== []) {
			$amountBreakdown = [
				'item_total' => [
					'currency_code' => $currency,
					'value' => $this->money($itemTotalCents),
				],
			] + $amountBreakdown;
		}

		$purchaseUnit = [
			'reference_id' => (string)$invoice->id,
			'custom_id' => (string)$invoice->id,
			'invoice_id' => (string)$invoice->invoice_number,
			'description' => $this->paypalText('Invoice #' . $invoice->invoice_number, 127, $this->invoiceLabel($invoice)),

			'payee' => [
				'merchant_id' => $merchantId,
			],

			'amount' => [
				'currency_code' => $currency,
				'value' => $this->money($totalCents),
				'breakdown' => $amountBreakdown,
			],
		];

		if ($items !== []) {
			$purchaseUnit['items'] = $items;
		}

		return [
			'intent' => 'CAPTURE',

			'application_context' => [
				'brand_name' => 'Billifty',
				'return_url' => $this->callbackUrl("pay/paypal/return/{$data->token}"),
				'cancel_url' => $this->callbackUrl("pay/paypal/cancel/{$data->token}"),
				'user_action' => 'PAY_NOW',
				'shipping_preference' => 'NO_SHIPPING',
			],

			'purchase_units' => [
				$purchaseUnit,
			],
		];
	}

	public function capturePayment(string $token): array
	{
		$provider = new PayPalClient;
		$provider->setApiCredentials(config('paypal'));
		$provider->getAccessToken();

		return $provider->capturePaymentOrder($token);
	}

	private function money(int $cents): string
	{
		return number_format($cents / 100, 2, '.', '');
	}

	protected function buildItems(Invoices $invoice, string $currency): array
	{
		$invoiceLabel = $this->invoiceLabel($invoice);

		return collect($invoice->items ?? [])
			->map(fn($item) => $this->buildInvoiceItem($item, $currency, $invoiceLabel))
			->filter()
			->values()
			->all();
	}

	protected function buildInvoiceItem(mixed $item, string $currency, string $invoiceLabel): ?array
	{
		$amountCents = $this->invoiceItemSubtotalCents($item);

		if ($amountCents <= 0) {
			return null;
		}

		$name = data_get($item, 'name') ?: data_get($item, 'description') ?: 'Invoice item';
		$description = data_get($item, 'description') ?: $invoiceLabel;
		$quantity = data_get($item, 'quantity');

		if (is_numeric($quantity)) {
			$description .= '; Qty: ' . $this->formatQuantity($quantity);
		}

		return $this->buildItem($currency, $amountCents, $name, $description);
	}

	protected function buildItem(string $currency, int $amountCents, string $name, string $description): array
	{
		return [
			'name' => $this->paypalText($name, 127, 'Invoice item'),
			'description' => $this->paypalText($description, 127, 'Invoice item'),
			'quantity' => '1',
			'unit_amount' => [
				'currency_code' => $currency,
				'value' => $this->money(max($amountCents, 0)),
			],
		];
	}

	protected function invoiceItemSubtotalCents(mixed $item): int
	{
		$lineTotalCents = data_get($item, 'line_total_cents');
		$taxCents = data_get($item, 'tax_cents', 0);

		if (is_numeric($lineTotalCents) && (int)$lineTotalCents > 0) {
			return max((int)$lineTotalCents - (int)(is_numeric($taxCents) ? $taxCents : 0), 0);
		}

		$quantity = data_get($item, 'quantity', 1);
		$quantity = is_numeric($quantity) ? (float)$quantity : 1.0;

		$unitAmountCents = data_get($item, 'unit_price_cents', 0);
		$unitAmountCents = is_numeric($unitAmountCents) ? (int)$unitAmountCents : 0;

		return max((int)round($quantity * $unitAmountCents), 0);
	}

	protected function itemsTotalCents(array $items): int
	{
		return collect($items)->sum(function (array $item): int {
			$quantity = max((int)($item['quantity'] ?? 1), 1);
			$unitAmount = data_get($item, 'unit_amount.value', '0.00');

			return $quantity * (int)round((float)$unitAmount * 100);
		});
	}

	protected function invoiceLabel(Invoices $invoice): string
	{
		return 'Invoice #' . ($invoice->invoice_number ?: $invoice->id);
	}

	private function paypalText(?string $value, int $maxLength, string $fallback): string
	{
		$text = trim((string)$value);
		$text = preg_replace('/\s+/', ' ', $text) ?: '';

		if ($text === '') {
			$text = $fallback;
		}

		return substr($text, 0, $maxLength);
	}

	private function formatQuantity(mixed $quantity): string
	{
		return rtrim(rtrim(number_format((float)$quantity, 4, '.', ''), '0'), '.');
	}

	private function callbackUrl(string $path): string
	{
		$path = ltrim($path, '/');

		if (function_exists('app') && app()->bound('url')) {
			return url($path);
		}

		return '/' . $path;
	}
}
