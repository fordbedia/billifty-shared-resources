<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Tests\Repository\Eloquents;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents\InvoiceRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Workspace;
use BilliftySDK\SharedResources\TestCase\Migrations\BaseTest;
use BilliftySDK\SharedResources\TestCase\Scenario\CreateInvoice;
use Illuminate\Support\Facades\Auth;

class InvoiceRepositoryTest extends BaseTest
{
	protected $scenario;
	protected InvoiceContracts $repo;

	public function setUp(): void
	{
		parent::setUp();

		$this->scenario = (new CreateInvoice())();
		$this->repo = app(InvoiceRepository::class);
	}

	public function test_if_auto_generated_invoice_number_is_uniquely_incremented()
	{
		$invoice = $this->scenario['invoice'];
		$user = $this->scenario['user'];
		$workspace = $this->scenario['workspace'];

		$invoiceNumber = $invoice->invoice_number;

		$newInvoiceNumber = $this->repo->autoInvoiceNumber();

		$this->assertSame('INV-00002', $newInvoiceNumber);
		$this->assertNotSame($invoiceNumber, $newInvoiceNumber);
		$this->assertNull($this->repo->duplicateInvoice($newInvoiceNumber));

		$nextInvoice = $invoice->replicate();
		$nextInvoice->invoice_number = $newInvoiceNumber;
		$nextInvoice->created_at = now()->addSecond();
		$nextInvoice->updated_at = now()->addSecond();
		$nextInvoice->save();

		$this->assertSame('INV-00003', $this->repo->autoInvoiceNumber());
	}

	public function test_if_method_findById_returns_invoice_object()
	{
		$invoice = $this->scenario['invoice'];

		$userInvoice = $this->repo->findById($invoice->id);

		$this->assertEquals($invoice->id, $userInvoice->id);
		$this->assertInstanceOf(Invoices::class, $userInvoice);
	}
}
