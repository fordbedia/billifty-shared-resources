<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Tests\Support;

use BilliftySDK\SharedResources\Modules\Billing\Support\PlanPermission;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents\InvoiceRepository;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use BilliftySDK\SharedResources\TestCase\Migrations\BaseTest;
use BilliftySDK\SharedResources\TestCase\Scenario\CreateInvoice;
use BilliftySDK\SharedResources\TestCase\Scenario\TestScenarioCollection;

class PlanPermissionScenarioTest extends BaseTest
{
	private TestScenarioCollection $scenario;
	private InvoiceRepository $repo;

	public function setUp(): void
	{
		parent::setUp();

		$this->scenario = (new CreateInvoice(planType: 'premium'))();
		$this->repo = app(InvoiceRepository::class);
	}

	/** @test */
	public function documented_helper_methods_work_with_seeded_plan_capabilities(): void
	{
		$user = $this->scenarioUser();
		$permission = PlanPermission::attempt($user);

		foreach ([
			'upload business logo',
			'custom brand colors',
			'custom invoice numbering',
			'online payments',
			'multi currency',
			'ai invoice assistant',
			'automated reminders',
		] as $ability) {
			$this->assertTrue($permission->can($ability), "Expected [{$ability}] to be allowed.");
		}

		$this->assertTrue((new PlanPermission)->forUser($user)->can('upload business logo'));
		$this->assertTrue($permission->can('create invoice'));
		$this->assertTrue($permission->can('create client'));
		$this->assertTrue($permission->can('create business profile'));
		$this->assertTrue($permission->canWithinLimit('max_invoices_per_month', 999));
		$this->assertTrue($permission->canWithinLimit('max_clients', 999));
		$this->assertTrue($permission->canWithinLimit('max_business_profiles', 999));
	}

	/** @test */
	public function documented_raw_values_payload_and_watermark_aliases_work_with_seeded_plan_capabilities(): void
	{
		$user = $this->scenarioUser();
		$permission = PlanPermission::attempt($user);

		$this->assertTrue($permission->get('logo_upload'));
		$this->assertSame('all_advanced', $permission->get('templates_tier'));
		$this->assertNull($permission->get('max_clients'));
		$this->assertTrue($permission->can('remove pdf watermark'));
		$this->assertTrue($permission->can('no pdf watermark'));
		$this->assertTrue($permission->can('remove email watermark'));
		$this->assertTrue($permission->can('no email watermark'));

		$payload = $permission->toArray();

		$this->assertSame('premium', $payload['plan']['code']);
		$this->assertNull($payload['limits']['max_business_profiles']);
		$this->assertNull($payload['limits']['max_clients']);
		$this->assertNull($payload['limits']['max_invoices_per_month']);
		$this->assertArrayHasKey('current:businessProfiles', $payload['limits']);
		$this->assertArrayHasKey('current:clients', $payload['limits']);
		$this->assertArrayHasKey('current:invoices', $payload['limits']);
		$this->assertTrue($payload['flags']['logo_upload']);
		$this->assertSame('all_advanced', $payload['flags']['templates_tier']);
		$this->assertTrue($payload['allowed']['create:businessProfiles']);
		$this->assertTrue($payload['allowed']['create:clients']);
		$this->assertTrue($payload['allowed']['create:invoices']);
		$this->assertTrue($payload['allowed']['logo_upload']);
		$this->assertFalse($payload['not_allowed']['logo_upload']);
	}

	private function scenarioUser(): User
	{
		return $this->scenario->get('user')->refresh();
	}
}
