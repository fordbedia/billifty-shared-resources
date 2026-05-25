<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Tests\Infrastructure\Payments\Traits\Security;

use BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments\Traits\Security\ValidatesInvoiceState;
use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceStatus;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\TestCase\Migrations\BaseTest;
use BilliftySDK\SharedResources\TestCase\Scenario\CreateInvoice;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidatesInvoiceStateTest extends BaseTest
{
	public function test_it_restricts_payment_links_if_invoice_is_not_issued(): void
	{
		$scenario = CreateInvoice::make(planType: 'free');

		$this->assertDatabaseHas('plans', [
			'id' => $scenario['plan']->id,
			'code' => 'free',
		]);
		$this->assertDatabaseHas('users', [
			'id' => $scenario['user']->id,
			'email' => 'johndoe+test1@gmail.com',
			'plan_id' => $scenario['plan']->id,
		]);

		$validator = new class {
			use ValidatesInvoiceState;
		};
		$invoice = new Invoices([
			'status' => InvoiceStatus::DRAFT->value,
		]);

		try {
			$validator->invoiceIssued($invoice);
			$this->fail('Non-issued invoices must not create payment links.');
		} catch (HttpException $exception) {
			$this->assertSame(403, $exception->getStatusCode());
			$this->assertSame('Something went wrong. Invoice needs to be issued first.', $exception->getMessage());
		}
	}
}
