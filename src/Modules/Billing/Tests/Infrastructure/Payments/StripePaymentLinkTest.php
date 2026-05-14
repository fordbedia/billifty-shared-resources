<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Tests\Infrastructure\Payments;

use BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments\StripePaymentLink;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceItems;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class StripePaymentLinkTest extends TestCase
{
	/** @test */
	public function it_itemizes_checkout_line_items_while_reconciling_to_invoice_total_cents(): void
	{
		$invoice = new Invoices([
			'id' => 123,
			'invoice_number' => 'INV-1001',
			'subtotal_cents' => 6000,
			'tax_cents' => 300,
			'discount_cents' => 500,
			'shipping_cents' => 1000,
			'shipping_tax_cents' => 83,
			'total_cents' => 6883,
		]);
		$invoice->setRelation('items', collect([
			new InvoiceItems([
				'name' => 'Consulting',
				'description' => 'Strategy session',
				'quantity' => 2,
				'unit_price_cents' => 1500,
				'tax_cents' => 300,
				'line_total_cents' => 3300,
			]),
			new InvoiceItems([
				'name' => 'Implementation',
				'quantity' => 1.5,
				'unit_price_cents' => 2000,
				'tax_cents' => 0,
				'line_total_cents' => 3000,
			]),
		]));

		$lineItems = $this->buildLineItems($invoice, 'usd');
		$lineItemsTotal = $this->lineItemsTotal($lineItems);
		$discountCents = $this->checkoutDiscountCents($invoice, $lineItems);

		$this->assertCount(3, $lineItems);
		$this->assertSame(1, $lineItems[0]['quantity']);
		$this->assertSame('usd', $lineItems[0]['price_data']['currency']);
		$this->assertSame(3300, $lineItems[0]['price_data']['unit_amount']);
		$this->assertSame('Consulting', $lineItems[0]['price_data']['product_data']['name']);
		$this->assertSame('Invoice #INV-1001; Qty: 2', $lineItems[0]['price_data']['product_data']['description']);
		$this->assertSame(3000, $lineItems[1]['price_data']['unit_amount']);
		$this->assertSame('Implementation', $lineItems[1]['price_data']['product_data']['name']);
		$this->assertSame('Invoice #INV-1001; Qty: 1.5', $lineItems[1]['price_data']['product_data']['description']);
		$this->assertSame(1083, $lineItems[2]['price_data']['unit_amount']);
		$this->assertSame('Shipping', $lineItems[2]['price_data']['product_data']['name']);
		$this->assertSame(7383, $lineItemsTotal);
		$this->assertSame(500, $discountCents);
		$this->assertSame($invoice->total_cents, $lineItemsTotal - $discountCents);
		$this->assertNotSame($invoice->subtotal_cents, $lineItemsTotal - $discountCents);
	}

	private function buildLineItems(Invoices $invoice, string $currencyCode): array
	{
		return $this->invokeStripePaymentLinkMethod('buildLineItems', $invoice, $currencyCode);
	}

	private function lineItemsTotal(array $lineItems): int
	{
		return $this->invokeStripePaymentLinkMethod('lineItemsTotal', $lineItems);
	}

	private function checkoutDiscountCents(Invoices $invoice, array $lineItems): int
	{
		return $this->invokeStripePaymentLinkMethod('checkoutDiscountCents', $invoice, $lineItems);
	}

	private function invokeStripePaymentLinkMethod(string $methodName, mixed ...$arguments): mixed
	{
		$reflection = new ReflectionClass(StripePaymentLink::class);
		$gateway = $reflection->newInstanceWithoutConstructor();
		$method = $reflection->getMethod($methodName);
		$method->setAccessible(true);

		return $method->invoke($gateway, ...$arguments);
	}
}
