<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Tests\Infrastructure\Payments\Traits\Security;

use BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments\Traits\Security\ValidatesInvoiceState;
use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceStatus;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\TestCase\BaseTest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidatesInvoiceStateTest extends BaseTest
{
	public function test_it_restricts_payment_links_if_invoice_is_not_issued(): void
	{
		$validator = $this->validator();
		$invoice = new Invoices([
			'status' => InvoiceStatus::DRAFT->value,
		]);

		try {
			$validator->validateInvoiceState($invoice);
			$this->fail('Non-issued invoices must not create payment links.');
		} catch (HttpException $exception) {
			$this->assertSame(403, $exception->getStatusCode());
			$this->assertSame('Something went wrong. Invoice needs to be issued first.', $exception->getMessage());
		}
	}

	public function test_it_restricts_payment_links_if_invoice_is_already_paid(): void
	{
		$validator = $this->validator();
		$invoice = new Invoices([
			'status' => InvoiceStatus::PAID->value,
		]);

		try {
			$validator->validateInvoiceState($invoice);
			$this->fail('Paid invoices must not create payment links.');
		} catch (HttpException $exception) {
			$this->assertSame(403, $exception->getStatusCode());
			$this->assertSame('This invoice is already paid. You cannot create a new payment link for it.', $exception->getMessage());
		}
	}

	public function test_it_restricts_invoice_downloads_if_invoice_is_draft(): void
	{
		$validator = $this->validator();
		$invoice = new Invoices([
			'status' => InvoiceStatus::DRAFT->value,
		]);

		try {
			$validator->validateInvoiceDraftState($invoice);
			$this->fail('Draft invoices must not be downloaded.');
		} catch (HttpException $exception) {
			$this->assertSame(403, $exception->getStatusCode());
			$this->assertSame('Something went wrong. Invoice needs to be issued first.', $exception->getMessage());
		}
	}

	/**
	 * @dataProvider nonDraftInvoiceStatusProvider
	 */
	public function test_it_allows_invoice_downloads_when_invoice_is_not_draft(InvoiceStatus $status): void
	{
		$validator = $this->validator();
		$invoice = new Invoices([
			'status' => $status,
		]);

		$validator->validateInvoiceDraftState($invoice);

		$this->assertTrue(true);
	}

	public function test_it_restricts_paid_invoices_with_default_message(): void
	{
		$validator = $this->validator();
		$invoice = new Invoices([
			'status' => InvoiceStatus::PAID->value,
		]);

		try {
			$validator->validateInvoicePaidState($invoice);
			$this->fail('Paid invoices must be restricted.');
		} catch (HttpException $exception) {
			$this->assertSame(403, $exception->getStatusCode());
			$this->assertSame('This invoice is already paid. You cannot create a new payment link for it.', $exception->getMessage());
		}
	}

	public function test_it_restricts_paid_invoices_with_custom_message(): void
	{
		$validator = $this->validator();
		$invoice = new Invoices([
			'status' => InvoiceStatus::PAID,
		]);

		try {
			$validator->validateInvoicePaidState($invoice, 'This invoice cannot be downloaded.');
			$this->fail('Paid invoices must be restricted.');
		} catch (HttpException $exception) {
			$this->assertSame(403, $exception->getStatusCode());
			$this->assertSame('This invoice cannot be downloaded.', $exception->getMessage());
		}
	}

	/**
	 * @dataProvider nonPaidInvoiceStatusProvider
	 */
	public function test_it_allows_non_paid_invoices(InvoiceStatus $status): void
	{
		$validator = $this->validator();
		$invoice = new Invoices([
			'status' => $status,
		]);

		$validator->validateInvoicePaidState($invoice);

		$this->assertTrue(true);
	}

	public static function nonDraftInvoiceStatusProvider(): array
	{
		return [
			'issued' => [InvoiceStatus::ISSUED],
			'paid' => [InvoiceStatus::PAID],
			'partially' => [InvoiceStatus::PARTIALLY],
			'sent' => [InvoiceStatus::SENT],
			'void' => [InvoiceStatus::VOID],
		];
	}

	public static function nonPaidInvoiceStatusProvider(): array
	{
		return [
			'draft' => [InvoiceStatus::DRAFT],
			'issued' => [InvoiceStatus::ISSUED],
			'partially' => [InvoiceStatus::PARTIALLY],
			'sent' => [InvoiceStatus::SENT],
			'void' => [InvoiceStatus::VOID],
		];
	}

	private function validator(): object
	{
		return new class {
			use ValidatesInvoiceState;
		};
	}
}
