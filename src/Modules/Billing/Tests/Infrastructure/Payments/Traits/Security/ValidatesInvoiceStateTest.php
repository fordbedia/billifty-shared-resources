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
		$validator = new class {
			use ValidatesInvoiceState;
		};
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
		$validator = new class {
			use ValidatesInvoiceState;
		};
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
}
