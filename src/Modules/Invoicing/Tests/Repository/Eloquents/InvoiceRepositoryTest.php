<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Tests\Repository\Eloquents;

use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents\InvoiceRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Workspace;
use BilliftySDK\SharedResources\TestCase\Migrations\BaseTest;
use BilliftySDK\SharedResources\TestCase\Scenario\CreateInvoice;
use Illuminate\Support\Facades\Auth;

class InvoiceRepositoryTest extends BaseTest
{
	protected $scenario;

	public function setUp(): void
	{
		parent::setUp();

		$this->scenario = (new CreateInvoice())();
	}

	public function test_if_auto_generated_invoice_number_is_uniquely_incremented()
	{
		$invoice = $this->scenario['invoice'];
		$user = $this->scenario['user'];
		$workspace = $this->scenario['workspace'];

		$invoiceNumber = $invoice->invoice_number;

		Workspace::query()
			->where('user_id', $user->id)
			->whereKeyNot($workspace->id)
			->update(['is_default' => 0]);

		$workspace->forceFill(['is_default' => 1])->save();

		Auth::login($user);

		$repo = app(InvoiceRepository::class);

		$newInvoiceNumber = $repo->autoInvoiceNumber();

		$this->assertSame('INV-00002', $newInvoiceNumber);
		$this->assertNotSame($invoiceNumber, $newInvoiceNumber);
		$this->assertNull($repo->duplicateInvoice($newInvoiceNumber));

		$nextInvoice = $invoice->replicate();
		$nextInvoice->invoice_number = $newInvoiceNumber;
		$nextInvoice->created_at = now()->addSecond();
		$nextInvoice->updated_at = now()->addSecond();
		$nextInvoice->save();

		$this->assertSame('INV-00003', $repo->autoInvoiceNumber());
	}
}
