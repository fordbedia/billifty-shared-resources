<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Tests\Infrastructure\Repository\Eloquent;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Repository\Eloquent\EloquentAdvancedFilterOptionRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\BusinessProfiles;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Clients;
use BilliftySDK\SharedResources\TestCase\Migrations\BaseTest;
use BilliftySDK\SharedResources\TestCase\Scenario\CreateInvoice;

class EloquentAdvancedFilterOptionRepositoryTest extends BaseTest
{
	protected $scenario;
	protected EloquentAdvancedFilterOptionRepository $repo;

	public function setUp(): void
	{
		parent::setUp();

		$this->scenario = (new CreateInvoice())();
		$this->repo = app(EloquentAdvancedFilterOptionRepository::class);
	}

	public function test_default_workspace_id_for_user_returns_the_users_default_workspace(): void
	{
		$user = $this->scenario['user'];
		$workspace = $this->scenario['workspace'];

		$this->assertSame($workspace->id, $this->repo->defaultWorkspaceIdForUser($user->id));
	}

	public function test_invoice_numbers_filters_by_workspace_and_search(): void
	{
		$workspace = $this->scenario['workspace'];
		$invoice = $this->scenario['invoice'];

		$otherWorkspaceInvoice = $invoice->replicate();
		$otherWorkspaceInvoice->forceFill([
			'workspace_id' => $this->createAnotherWorkspaceId(),
			'invoice_number' => 'INV-OTHER-001',
			'created_at' => now()->addMinute(),
			'updated_at' => now()->addMinute(),
		])->save();

		$results = $this->repo->invoiceNumbers($workspace->id, '', 1, 15);

		$this->assertTrue($results->contains($invoice->invoice_number));
		$this->assertFalse($results->contains('INV-OTHER-001'));

		$searched = $this->repo->invoiceNumbers($workspace->id, $invoice->invoice_number, 1, 15);

		$this->assertTrue($searched->contains($invoice->invoice_number));

		$this->assertTrue($this->repo->invoiceNumbers($workspace->id, 'NO-MATCH', 1, 15)->isEmpty());
	}

	public function test_business_profiles_searches_by_name_legal_name_and_email(): void
	{
		$workspace = $this->scenario['workspace'];
		$businessProfile = $this->scenario['businessProfile'];

		$otherProfile = BusinessProfiles::create([
			'workspace_id' => $workspace->id,
			'name' => 'Acme Studio',
			'legal_name' => 'Acme Studio LLC',
			'email' => 'billing@acmestudio.test',
			'logo_disk' => 'public',
			'is_test' => 1,
		]);

		$byName = $this->repo->businessProfiles($workspace->id, $businessProfile->name, 1, 15);
		$this->assertTrue($byName->pluck('id')->contains($businessProfile->id));
		$this->assertFalse($byName->pluck('id')->contains($otherProfile->id));

		$byEmail = $this->repo->businessProfiles($workspace->id, 'billing@acmestudio.test', 1, 15);
		$this->assertTrue($byEmail->pluck('id')->contains($otherProfile->id));
		$this->assertFalse($byEmail->pluck('id')->contains($businessProfile->id));

		$all = $this->repo->businessProfiles($workspace->id, '', 1, 15);
		$this->assertSame(2, $all->count());
	}

	public function test_clients_searches_by_name_and_email_scoped_to_workspace(): void
	{
		$workspace = $this->scenario['workspace'];
		$client = $this->scenario['client'];

		$otherWorkspaceClient = Clients::create([
			'workspace_id' => $this->createAnotherWorkspaceId(),
			'name' => $client->name,
			'is_test' => 1,
		]);

		$results = $this->repo->clients($workspace->id, $client->name, 1, 15);

		$this->assertTrue($results->pluck('id')->contains($client->id));
		$this->assertFalse($results->pluck('id')->contains($otherWorkspaceClient->id));

		$this->assertTrue($this->repo->clients($workspace->id, 'NO-MATCH', 1, 15)->isEmpty());
	}

	private function createAnotherWorkspaceId(): int
	{
		$otherUser = \BilliftySDK\SharedResources\Modules\User\Models\User::create([
			'plan_id' => $this->scenario['plan']->id,
			'fname' => 'Jane',
			'lname' => 'Doe',
			'email' => 'janedoe+' . uniqid() . '@example.com',
			'password' => bcrypt('password'),
		]);

		return $otherUser->resolveDefaultWorkspace()->id;
	}
}
