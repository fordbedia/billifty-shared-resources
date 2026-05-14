<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Tests\Domain;

use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceStateMachine;
use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceStatus;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceItems;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\TestCase\BaseTest;
use DomainException;

class InvoiceStateMachineTest extends BaseTest
{
	/** @test */
	public function it_allows_matching_invoice_items_after_issue(): void
	{
		$invoice = $this->issuedInvoiceWithItems([
			new InvoiceItems([
				'id' => 10,
				'description' => 'Line item',
				'quantity' => '2.0000',
				'unit_price_cents' => 1000,
				'tax_rate' => '0.0000',
				'line_discount_cents' => 0,
				'line_discount_rate' => '0.0000',
				'tax_cents' => 0,
				'line_total_cents' => 2000,
			]),
		]);

		InvoiceStateMachine::assertMutableFields($invoice, [
			'invoice_items' => [
				[
					'id' => 10,
					'description' => 'Line item',
					'quantity' => 2,
					'unit_price_cents' => 1000,
					'tax_rate' => 0,
					'line_discount_cents' => 0,
					'line_discount_rate' => 0,
					'tax_cents' => 0,
					'line_total_cents' => 2000,
				],
			],
		]);

		$this->assertTrue(true);
	}

	/** @test */
	public function it_rejects_modified_invoice_item_fields_after_issue(): void
	{
		$invoice = $this->issuedInvoiceWithItems([
			new InvoiceItems([
				'id' => 10,
				'description' => 'Line item',
				'quantity' => 2,
				'unit_price_cents' => 1000,
				'tax_rate' => 0,
				'line_discount_cents' => 0,
				'line_discount_rate' => 0,
				'tax_cents' => 0,
				'line_total_cents' => 2000,
			]),
		]);

		$this->expectException(DomainException::class);
		$this->expectExceptionMessage('Field Quantity cannot be modified after issuing.');

		InvoiceStateMachine::assertMutableFields($invoice, [
			'invoice_items' => [
				[
					'id' => 10,
					'description' => 'Line item',
					'quantity' => 3,
				],
			],
		]);
	}

	/** @test */
	public function it_rejects_added_invoice_items_after_issue(): void
	{
		$invoice = $this->issuedInvoiceWithItems([
			new InvoiceItems([
				'id' => 10,
				'description' => 'Line item',
				'quantity' => 2,
			]),
		]);

		$this->expectException(DomainException::class);
		$this->expectExceptionMessage('Invoice items cannot be added or removed after issuing.');

		InvoiceStateMachine::assertMutableFields($invoice, [
			'invoice_items' => [
				[
					'id' => 10,
					'description' => 'Line item',
					'quantity' => 2,
				],
				[
					'description' => 'New line item',
					'quantity' => 1,
				],
			],
		]);
	}

	private function issuedInvoiceWithItems(array $items): Invoices
	{
		$invoice = new Invoices([
			'status' => InvoiceStatus::ISSUED->value,
		]);

		$invoice->setRelation('items', collect($items));

		return $invoice;
	}
}
