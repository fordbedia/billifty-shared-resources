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

	public function test_it_allows_invoice_downloads_when_invoice_is_issued(): void
	{
		$validator = $this->validator();
		$invoice = new Invoices([
			'status' => InvoiceStatus::ISSUED->value,
		]);

		$validator->validateInvoiceIssuedOrPaid($invoice);

		$this->assertTrue(true);
	}

	public function test_it_allows_invoice_downloads_when_invoice_is_paid(): void
	{
		$validator = $this->validator();
		$invoice = new Invoices([
			'status' => InvoiceStatus::PAID,
		]);

		$validator->validateInvoiceIssuedOrPaid($invoice);

		$this->assertTrue(true);
	}

	/**
	 * @dataProvider nonIssuedOrPaidInvoiceStatusProvider
	 */
	public function test_it_restricts_invoice_downloads_when_invoice_is_not_issued_or_paid(string $status): void
	{
		$validator = $this->validator();
		$invoice = new Invoices([
			'status' => $status,
		]);

		try {
			$validator->validateInvoiceIssuedOrPaid($invoice);
			$this->fail('Only issued or paid invoices can be downloaded.');
		} catch (HttpException $exception) {
			$this->assertSame(403, $exception->getStatusCode());
			$this->assertSame('Something went wrong. Invoice needs to be issued first.', $exception->getMessage());
		}
	}

	public static function nonIssuedOrPaidInvoiceStatusProvider(): array
	{
		return [
			'draft' => [InvoiceStatus::DRAFT->value],
			'partially' => [InvoiceStatus::PARTIALLY->value],
			'sent' => [InvoiceStatus::SENT->value],
			'void' => [InvoiceStatus::VOID->value],
		];
	}

	private function validator(): object
	{
		return new class {
			use ValidatesInvoiceState;
		};
	}
}
