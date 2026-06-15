<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Tests\Infrastructure\Payments;

use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;
use BilliftySDK\SharedResources\Modules\Billing\DTO\CreateInvoicePaymentLinkData;
use BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments\PayPalGateway;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceItems;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\TestCase\Migrations\BaseTest;
use ReflectionClass;

class PayPalGatewayTest extends BaseTest
{
	/** @test */
	public function it_builds_paypal_items_with_required_names_and_whole_number_quantities(): void
	{
		$this->assertDumpWasRestored();

		$invoice = $this->createInvoice([
			'invoice_number' => 'INV-2001',
			'tax_cents' => 0,
			'shipping_cents' => 0,
			'shipping_tax_cents' => 0,
			'total_cents' => 25000,
		]);

		InvoiceItems::create([
			'invoice_id' => $invoice->id,
			'name' => null,
			'description' => null,
			'quantity' => '100.0000',
			'unit_price_cents' => 250,
			'tax_cents' => 0,
			'line_total_cents' => 25000,
		]);

		$payload = $this->buildOrderPayload($invoice->fresh('items'));
		$item = $payload['purchase_units'][0]['items'][0];

		$this->assertSame('Invoice item', $item['name']);
		$this->assertSame('1', $item['quantity']);
		$this->assertSame('250.00', $item['unit_amount']['value']);
		$this->assertSame('250.00', $payload['purchase_units'][0]['amount']['breakdown']['item_total']['value']);
		$this->assertSame('250.00', $payload['purchase_units'][0]['amount']['value']);
	}

	/** @test */
	public function it_reconciles_paypal_breakdown_to_the_invoice_total(): void
	{
		$this->assertDumpWasRestored();

		$invoice = $this->createInvoice([
			'invoice_number' => 'INV-1001',
			'tax_cents' => 300,
			'discount_cents' => 500,
			'shipping_cents' => 1000,
			'shipping_tax_cents' => 83,
			'total_cents' => 6883,
		]);

		InvoiceItems::create([
			'invoice_id' => $invoice->id,
			'name' => 'Consulting',
			'description' => 'Strategy session',
			'quantity' => 2,
			'unit_price_cents' => 1500,
			'tax_cents' => 300,
			'line_total_cents' => 3300,
		]);

		InvoiceItems::create([
			'invoice_id' => $invoice->id,
			'name' => 'Implementation',
			'quantity' => 1.5,
			'unit_price_cents' => 2000,
			'tax_cents' => 0,
			'line_total_cents' => 3000,
		]);

		$payload = $this->buildOrderPayload($invoice->fresh('items'));
		$purchaseUnit = $payload['purchase_units'][0];

		$this->assertSame('68.83', $purchaseUnit['amount']['value']);
		$this->assertSame('60.00', $purchaseUnit['amount']['breakdown']['item_total']['value']);
		$this->assertSame('3.83', $purchaseUnit['amount']['breakdown']['tax_total']['value']);
		$this->assertSame('10.00', $purchaseUnit['amount']['breakdown']['shipping']['value']);
		$this->assertSame('5.00', $purchaseUnit['amount']['breakdown']['discount']['value']);
		$this->assertSame('30.00', $purchaseUnit['items'][0]['unit_amount']['value']);
		$this->assertSame('Strategy session; Qty: 2', $purchaseUnit['items'][0]['description']);
		$this->assertSame('30.00', $purchaseUnit['items'][1]['unit_amount']['value']);
		$this->assertSame('Invoice #INV-1001; Qty: 1.5', $purchaseUnit['items'][1]['description']);
	}

	private function assertDumpWasRestored(): void
	{
		$this->assertDatabaseHas('workspace', ['id' => 4]);
		$this->assertDatabaseHas('business_profiles', ['id' => 1]);
		$this->assertDatabaseHas('clients', ['id' => 1]);
		$this->assertDatabaseHas('invoice_templates', ['id' => 1]);
		$this->assertDatabaseHas('color_scheme', ['id' => 2]);
		$this->assertDatabaseHas('currency', ['id' => 1]);
	}

	private function createInvoice(array $attributes): Invoices
	{
		return Invoices::create(array_merge([
			'workspace_id' => 4,
			'business_profile_id' => 1,
			'client_id' => 1,
			'invoice_template_id' => 1,
			'color_scheme_id' => 2,
			'currency_id' => 1,
			'status' => 'issued',
			'subtotal_cents' => 0,
			'discount_mode' => 'none',
			'discount_cents' => 0,
			'discount_rate' => 0,
			'tax_cents' => 0,
			'shipping_cents' => 0,
			'shipping_tax_rate' => 0,
			'shipping_tax_cents' => 0,
			'total_cents' => 0,
			'amount_due_cents' => 0,
		], $attributes));
	}

	private function buildOrderPayload(Invoices $invoice): array
	{
		$reflection = new ReflectionClass(PayPalGateway::class);
		$gateway = $reflection->newInstanceWithoutConstructor();
		$method = $reflection->getMethod('buildOrderPayload');
		$method->setAccessible(true);

		return $method->invoke(
			$gateway,
			$invoice,
			new CreateInvoicePaymentLinkData(
				token: 'pay_test',
				provider: PaymentProvider::PAYPAL,
				successUrl: 'https://example.test/success',
				cancelUrl: 'https://example.test/cancel',
			),
			'USD',
			'merchant-test'
		);
	}
}
