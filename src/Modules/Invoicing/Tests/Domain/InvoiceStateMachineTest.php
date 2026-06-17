<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Tests\Domain;

use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceAction;
use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceStateMachine;
use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceStatus;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceItems;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\TestCase\BaseTest;
use DomainException;

class InvoiceStateMachineTest extends BaseTest
{
	/** @test */
	public function draft_invoices_can_be_saved_as_draft_saved_as_changes_or_issued(): void
	{
		$this->assertTrue(InvoiceStateMachine::canTransition(InvoiceStatus::DRAFT, InvoiceAction::SaveDraft));
		$this->assertTrue(InvoiceStateMachine::canTransition(InvoiceStatus::DRAFT, InvoiceAction::SaveChanges));
		$this->assertTrue(InvoiceStateMachine::canTransition(InvoiceStatus::DRAFT, InvoiceAction::Issue));
		$this->assertFalse(InvoiceStateMachine::canTransition(InvoiceStatus::DRAFT, InvoiceAction::Void));
	}

	/** @test */
	public function issued_invoices_can_only_be_saved_as_changes(): void
	{
		$this->assertFalse(InvoiceStateMachine::canTransition(InvoiceStatus::ISSUED, InvoiceAction::SaveDraft));
		$this->assertTrue(InvoiceStateMachine::canTransition(InvoiceStatus::ISSUED, InvoiceAction::SaveChanges));
		$this->assertFalse(InvoiceStateMachine::canTransition(InvoiceStatus::ISSUED, InvoiceAction::Issue));
		$this->assertFalse(InvoiceStateMachine::canTransition(InvoiceStatus::ISSUED, InvoiceAction::Void));
	}

	/** @test */
	public function paid_invoices_cannot_transition_to_any_action(): void
	{
		$this->assertFalse(InvoiceStateMachine::canTransition(InvoiceStatus::PAID, InvoiceAction::SaveDraft));
		$this->assertFalse(InvoiceStateMachine::canTransition(InvoiceStatus::PAID, InvoiceAction::SaveChanges));
		$this->assertFalse(InvoiceStateMachine::canTransition(InvoiceStatus::PAID, InvoiceAction::Issue));
		$this->assertFalse(InvoiceStateMachine::canTransition(InvoiceStatus::PAID, InvoiceAction::Void));
	}

	/** @test */
	public function void_invoices_cannot_transition_to_any_action(): void
	{
		$this->assertFalse(InvoiceStateMachine::canTransition(InvoiceStatus::VOID, InvoiceAction::SaveDraft));
		$this->assertFalse(InvoiceStateMachine::canTransition(InvoiceStatus::VOID, InvoiceAction::SaveChanges));
		$this->assertFalse(InvoiceStateMachine::canTransition(InvoiceStatus::VOID, InvoiceAction::Issue));
		$this->assertFalse(InvoiceStateMachine::canTransition(InvoiceStatus::VOID, InvoiceAction::Void));
	}

	/** @test */
	public function draft_invoices_can_mutate_invoice_fields_and_items(): void
	{
		$invoice = new Invoices([
			'status' => InvoiceStatus::DRAFT->value,
			'invoice_number' => 'INV-001',
			'total_cents' => 2000,
		]);

		$invoice->setRelation('items', collect([
			new InvoiceItems([
				'id' => 10,
				'description' => 'Old line item',
				'quantity' => 1,
			]),
		]));

		InvoiceStateMachine::assertMutableFields($invoice, [
			'invoice_number' => 'INV-002',
			'total_cents' => 3000,
			'invoice_items' => [
				[
					'description' => 'New line item',
					'quantity' => 2,
				],
			],
		]);

		$this->assertTrue(true);
	}

	/** @test */
	public function issued_invoices_reject_mutating_immutable_invoice_fields(): void
	{
		$invoice = new Invoices([
			'status' => InvoiceStatus::ISSUED->value,
			'invoice_number' => 'INV-001',
		]);

		$this->expectException(DomainException::class);
		$this->expectExceptionMessage('Field Invoice Number cannot be modified after issuing.');

		InvoiceStateMachine::assertMutableFields($invoice, [
			'invoice_number' => 'INV-002',
		]);
	}

	/** @test */
	public function issued_invoices_allow_mutating_fields_that_are_not_locked_after_issue(): void
	{
		$invoice = new Invoices([
			'status' => InvoiceStatus::ISSUED->value,
			'notes' => 'Old note',
			'terms' => 'Old terms',
		]);

		InvoiceStateMachine::assertMutableFields($invoice, [
			'notes' => 'Updated note',
			'terms' => 'Updated terms',
		]);

		$this->assertTrue(true);
	}

	/** @test */
	public function paid_invoices_reject_mutating_immutable_invoice_fields(): void
	{
		$invoice = new Invoices([
			'status' => InvoiceStatus::PAID->value,
			'total_cents' => 2000,
		]);

		$this->expectException(DomainException::class);
		$this->expectExceptionMessage('Field Total Cents cannot be modified after issuing.');

		InvoiceStateMachine::assertMutableFields($invoice, [
			'total_cents' => 3000,
		]);
	}

	/** @test */
	public function on_issue_marks_the_invoice_as_issued_and_sets_issued_at(): void
	{
		$invoice = new Invoices([
			'status' => InvoiceStatus::DRAFT->value,
			'issued_at' => null,
		]);

		InvoiceStateMachine::onIssue($invoice);

		$this->assertSame(InvoiceStatus::ISSUED->value, $invoice->status);
		$this->assertNotNull($invoice->issued_at);
	}

	/** @test */
	public function on_void_marks_the_invoice_as_void_and_sets_void_at(): void
	{
		$invoice = new Invoices([
			'status' => InvoiceStatus::ISSUED->value,
			'void_at' => null,
		]);

		InvoiceStateMachine::onVoid($invoice);

		$this->assertSame(InvoiceStatus::VOID->value, $invoice->status);
		$this->assertNotNull($invoice->void_at);
	}

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

	/** @test */
	public function it_rejects_mutating_void_invoices(): void
	{
		$invoice = new Invoices([
			'status' => InvoiceStatus::VOID->value,
		]);

		$this->expectException(DomainException::class);
		$this->expectExceptionMessage('Voided invoices cannot be modified.');

		InvoiceStateMachine::assertMutableFields($invoice, [
			'invoice_number' => 'INV-VOID-UPDATED',
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
