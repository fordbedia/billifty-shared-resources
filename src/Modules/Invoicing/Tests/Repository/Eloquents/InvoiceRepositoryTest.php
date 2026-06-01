<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Tests\Repository\Eloquents;

use BilliftySDK\SharedResources\Modules\Invoicing\Jobs\GenerateInvoicePdfJob;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents\InvoiceRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Support\PlaywrightPdfRenderer;
use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentLink;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use BilliftySDK\SharedResources\TestCase\Migrations\BaseTest;
use BilliftySDK\SharedResources\TestCase\Scenario\CreateInvoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Mockery;

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

	protected function tearDown(): void
	{
		Mockery::close();

		parent::tearDown();
	}

	public function test_if_auto_generated_invoice_number_is_uniquely_incremented()
	{
		$invoice = $this->scenario['invoice'];

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
		$this->assertTrue($userInvoice->relationLoaded('items'));
		$this->assertTrue($userInvoice->relationLoaded('workspace'));
		$this->assertTrue($userInvoice->workspace->relationLoaded('user'));
	}

	public function test_sync_items_updates_creates_and_deletes_invoice_items(): void
	{
		$invoice = $this->scenario['invoice'];
		$existingItems = $invoice->items()->orderBy('position')->get();
		$updatedItem = $existingItems->first();
		$deletedItem = $existingItems->last();

		$this->repo->syncItems($invoice, [
			[
				'id' => $updatedItem->id,
				'position' => 2,
				'description' => 'Updated logo design',
				'quantity' => 3,
				'unit_price_cents' => 12345,
				'_sortable_id' => 'client-side-only',
			],
			[
				'position' => 1,
				'description' => 'Monthly retainer',
				'quantity' => 1,
				'unit_price_cents' => 9999,
			],
		]);

		$this->assertSame(2, $invoice->items()->count());
		$this->assertDatabaseHas('invoice_items', [
			'id' => $updatedItem->id,
			'invoice_id' => $invoice->id,
			'position' => 2,
			'description' => 'Updated logo design',
			'unit_price_cents' => 12345,
		]);
		$this->assertDatabaseMissing('invoice_items', [
			'id' => $deletedItem->id,
		]);
		$this->assertDatabaseHas('invoice_items', [
			'invoice_id' => $invoice->id,
			'position' => 1,
			'description' => 'Monthly retainer',
			'unit_price_cents' => 9999,
		]);
	}

	public function test_make_model_returns_invoice_model_class(): void
	{
		$this->assertSame(Invoices::class, $this->repo->makeModel());
	}

	public function test_duplicate_invoice_only_checks_the_authenticated_users_default_workspace(): void
	{
		$invoice = $this->scenario['invoice'];
		$otherInvoice = $this->createInvoiceForAnotherUser('INV-OTHER-001');

		$duplicate = $this->repo->duplicateInvoice($invoice->invoice_number);

		$this->assertInstanceOf(Invoices::class, $duplicate);
		$this->assertSame($invoice->id, $duplicate->id);
		$this->assertNull($this->repo->duplicateInvoice($otherInvoice->invoice_number));

		Auth::logout();

		$this->assertNull($this->repo->duplicateInvoice($invoice->invoice_number));
	}

	public function test_delete_invoice_soft_deletes_an_invoice_for_the_authenticated_user(): void
	{
		$invoice = $this->scenario['invoice'];

		$this->assertSame(1, $this->repo->deleteInvoice($invoice->id));
		$this->assertSoftDeleted('invoices', [
			'id' => $invoice->id,
		]);
	}

	public function test_delete_invoice_does_not_delete_another_users_invoice(): void
	{
		$otherInvoice = $this->createInvoiceForAnotherUser();

		$this->assertSame(0, $this->repo->deleteInvoice($otherInvoice->id));
		$this->assertDatabaseHas('invoices', [
			'id' => $otherInvoice->id,
			'deleted_at' => null,
		]);
	}

	public function test_paginate_returns_authenticated_users_invoices_with_date_and_client_search_filters(): void
	{
		$invoice = $this->scenario['invoice'];
		$invoice->forceFill(['issued_on' => '2026-01-15'])->save();

		$invoice->replicate()->forceFill([
			'invoice_number' => 'INV-OUTSIDE-RANGE',
			'issued_on' => '2026-02-15',
			'created_at' => now()->addMinute(),
			'updated_at' => now()->addMinute(),
		])->save();

		$this->createInvoiceForAnotherUser('INV-OTHER-002');

		$results = $this->repo->paginate(
			perPage: 15,
			dateRange: ['start' => '2026-01-01', 'end' => '2026-01-31'],
			search: 'Cris'
		);

		$this->assertSame(1, $results->total());
		$this->assertSame($invoice->id, $results->first()->id);
		$this->assertTrue($results->first()->relationLoaded('items'));
	}

	public function test_find_by_key_returns_only_authenticated_users_invoice_with_relationships(): void
	{
		$invoice = $this->scenario['invoice'];
		$otherInvoice = $this->createInvoiceForAnotherUser();

		$found = $this->repo->findByKey($invoice->id);

		$this->assertInstanceOf(Invoices::class, $found);
		$this->assertSame($invoice->id, $found->id);
		$this->assertTrue($found->relationLoaded('workspace'));
		$this->assertTrue($found->relationLoaded('client'));
		$this->assertTrue($found->relationLoaded('items'));
		$this->assertNull($this->repo->findByKey($otherInvoice->id));
	}

	public function test_get_model_by_auth_user_scopes_the_invoice_query_to_the_authenticated_user(): void
	{
		$invoice = $this->scenario['invoice'];
		$otherInvoice = $this->createInvoiceForAnotherUser();

		$ids = $this->repo->getModelByAuthUser()->pluck('id')->all();

		$this->assertContains($invoice->id, $ids);
		$this->assertNotContains($otherInvoice->id, $ids);
	}

	public function test_get_model_by_workspace_scopes_to_the_authenticated_users_workspace(): void
	{
		$invoice = $this->scenario['invoice'];
		$otherInvoice = $this->createInvoiceForAnotherUser();

		$ownWorkspaceIds = $this->repo->getModelByWorkspace($this->scenario['workspace']->id)->pluck('id')->all();
		$otherWorkspaceIds = $this->repo->getModelByWorkspace($otherInvoice->workspace_id)->pluck('id')->all();

		$this->assertContains($invoice->id, $ownWorkspaceIds);
		$this->assertNotContains($otherInvoice->id, $ownWorkspaceIds);
		$this->assertSame([], $otherWorkspaceIds);
	}

	public function test_query_for_user_scopes_by_workspace_owner_and_empty_user_matches_nothing(): void
	{
		$invoice = $this->scenario['invoice'];
		$otherInvoice = $this->createInvoiceForAnotherUser();
		$repo = new InvoiceRepositoryTestProxy();

		$ids = $repo->queryForUserForTest($this->scenario['user']->id)->pluck('id')->all();

		$this->assertContains($invoice->id, $ids);
		$this->assertNotContains($otherInvoice->id, $ids);
		$this->assertSame(0, $repo->queryForUserForTest(null)->count());
	}

	public function test_default_workspace_id_for_user_returns_the_users_default_workspace_or_null(): void
	{
		$repo = new InvoiceRepositoryTestProxy();

		$this->assertSame($this->scenario['workspace']->id, $repo->defaultWorkspaceIdForUserForTest($this->scenario['user']->id));
		$this->assertNull($repo->defaultWorkspaceIdForUserForTest(null));
	}

	public function test_generate_pdf_generates_and_stores_invoice_pdf_without_calling_playwright(): void
	{
		$invoice = $this->scenario['invoice'];
		$invoice->forceFill(['issued_on' => '2026-01-15'])->save();
		PaymentLink::create([
			'invoice_id' => $invoice->id,
			'token' => 'test-payment-link-token',
			'expires_at' => now()->addDay(),
		]);
		Storage::fake('public');

		$renderer = Mockery::mock(PlaywrightPdfRenderer::class);
		$renderer->shouldReceive('render')
			->once()
			->with(Mockery::type('string'), Mockery::on(function (array $options): bool {
				return $options['format'] === 'A4'
					&& $options['printBackground'] === true
					&& $options['preferCSSPageSize'] === true;
			}))
			->andReturn('%PDF-1.4 fake pdf');

		$this->app->instance(PlaywrightPdfRenderer::class, $renderer);

		$result = $this->repo->generatePdf($invoice);

		$this->assertInstanceOf(Invoices::class, $result['invoice']);
		$this->assertStringStartsWith('invoice_pdfs/2026/01/inv_00001_', $result['path']);
		$this->assertStringEndsWith('.pdf', $result['path']);
		Storage::disk('public')->assertExists($result['path']);
		$this->assertSame($result['path'], $result['invoice']->pdf_path);
		$this->assertSame('public', $result['invoice']->pdf_disk);
	}

	public function test_queue_pdf_generation_dispatches_the_invoice_pdf_job(): void
	{
		Queue::fake();

		$invoice = $this->scenario['invoice'];

		$this->repo->queuePdfGeneration($invoice);

		Queue::assertPushed(GenerateInvoicePdfJob::class, function (GenerateInvoicePdfJob $job) use ($invoice): bool {
			return $job->invoiceId === $invoice->id;
		});
	}

	public function test_issue_marks_the_invoice_as_issued(): void
	{
		$invoice = $this->scenario['invoice'];

		$this->repo->issue($invoice);

		$invoice->refresh();

		$this->assertSame('issued', $invoice->status);
		$this->assertNotNull($invoice->issued_at);
	}

	private function createInvoiceForAnotherUser(string $invoiceNumber = 'INV-OTHER-001'): Invoices
	{
		$baseInvoice = $this->scenario['invoice'];
		$otherUser = User::create([
			'plan_id' => $this->scenario['plan']->id,
			'fname' => 'Jane',
			'lname' => 'Doe',
			'email' => 'janedoe+' . uniqid() . '@example.com',
			'password' => bcrypt('password'),
		]);

		$otherInvoice = $baseInvoice->replicate();
		$otherInvoice->forceFill([
			'workspace_id' => $otherUser->resolveDefaultWorkspace()->id,
			'invoice_number' => $invoiceNumber,
			'created_at' => now()->addMinute(),
			'updated_at' => now()->addMinute(),
		])->save();

		return $otherInvoice->refresh();
	}
}

class InvoiceRepositoryTestProxy extends InvoiceRepository
{
	public function queryForUserForTest(?int $userId): Builder
	{
		return $this->queryForUser($userId);
	}

	public function defaultWorkspaceIdForUserForTest(?int $userId): ?int
	{
		return $this->defaultWorkspaceIdForUser($userId);
	}
}
