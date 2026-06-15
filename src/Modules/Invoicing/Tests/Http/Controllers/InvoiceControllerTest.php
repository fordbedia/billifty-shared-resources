<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Tests\Http\Controllers;

use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceStatus;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\InvoiceController;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use BilliftySDK\SharedResources\Modules\Invoicing\Services\Reminders\InvoicePaymentReminderService;
use BilliftySDK\SharedResources\TestCase\BaseTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvoiceControllerTest extends BaseTest
{
	protected function tearDown(): void
	{
		Mockery::close();

		parent::tearDown();
	}

	public function test_send_to_client_rejects_void_invoices(): void
	{
		Bus::fake();
		Auth::shouldReceive('user')->once()->andReturn((object) ['id' => 7]);

		$invoice = new Invoices([
			'id' => 123,
			'status' => InvoiceStatus::VOID->value,
		]);

		$repo = new class($invoice) implements InvoiceContracts {
			public function __construct(private readonly Invoices $invoice)
			{
			}

			public function findById(int $id, ?int $userId = null): Invoices
			{
				return $this->invoice;
			}

			public function findByKey(int $id): ?Invoices
			{
				return $this->invoice;
			}

			public function queuePdfGeneration(Invoices $invoice): void
			{
			}
		};

		$controller = new InvoiceController();

		try {
			$controller->sendToClient(
				123,
				Request::create('/invoices/123/send-to-client', 'POST'),
				$repo,
				Mockery::mock(InvoicePaymentReminderService::class)
			);

			$this->fail('Void invoices must not be sent directly to clients.');
		} catch (HttpException $exception) {
			$this->assertSame(403, $exception->getStatusCode());
			$this->assertSame('This invoice has been voided and can no longer be paid.', $exception->getMessage());
		}

		Bus::assertNothingDispatched();
	}
}
