<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Tests\Support;

use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\Billing\Support\PlanPermission;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\PlanCapability;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use BilliftySDK\SharedResources\Modules\User\Service\PlanCapabilityService;
use BilliftySDK\SharedResources\TestCase\BaseTest;

class PlanPermissionTest extends BaseTest
{
	/** @test */
	public function to_array_uses_the_subscription_plan_over_the_user_plan(): void
	{
		$userPlan = $this->plan(1, 'free', 'Free');
		$subscriptionPlan = $this->plan(2, 'pro', 'Pro');
		$subscription = new UserSubscription([
			'plan_id' => $subscriptionPlan->id,
			'plan_code' => $subscriptionPlan->code,
		]);
		$subscription->setRelation('plan', $subscriptionPlan);

		$user = new User(['plan_id' => $userPlan->id]);
		$user->setRelation('plan', $userPlan);
		$user->setRelation('subscription', $subscription);

		$result = (new PlanPermission($user))->toArray();

		$this->assertSame([
			'id' => 2,
			'code' => 'pro',
			'name' => 'Pro',
		], $result['plan']);
	}

	/** @test */
	public function to_array_falls_back_to_the_user_plan_when_no_subscription_exists(): void
	{
		$userPlan = $this->plan(1, 'free', 'Free');
		$user = new User(['plan_id' => $userPlan->id]);
		$user->setRelation('plan', $userPlan);
		$user->setRelation('subscription', null);

		$result = (new PlanPermission($user))->toArray();

		$this->assertSame([
			'id' => 1,
			'code' => 'free',
			'name' => 'Free',
		], $result['plan']);
	}

	/** @test */
	public function capability_checks_use_the_subscription_plan_over_the_user_plan(): void
	{
		$userPlan = $this->plan(1, 'free', 'Free', [
			$this->capability('online_payments', 'features', 'bool', 'false'),
			$this->capability('max_clients', 'limits', 'int', '1', 'clients'),
		]);
		$subscriptionPlan = $this->plan(2, 'premium', 'Premium', [
			$this->capability('online_payments', 'features', 'bool', 'true'),
			$this->capability('max_clients', 'limits', 'int', '0', 'clients', ['unlimited' => true]),
		]);
		$user = $this->userWithPlans($userPlan, $subscriptionPlan);
		$permission = new PlanPermission($user);

		$this->assertTrue($permission->has('online_payments'));
		$this->assertNull($permission->get('max_clients'));
		$this->assertSame('clients', $permission->relationship('max_clients'));
		$this->assertTrue($permission->canWithinLimit('max_clients', 999));
	}

	/** @test */
	public function plan_capability_service_uses_the_subscription_plan_through_plan_permission(): void
	{
		$userPlan = $this->plan(1, 'free', 'Free', [
			$this->capability('online_payments', 'features', 'bool', 'false'),
		]);
		$subscriptionPlan = $this->plan(2, 'premium', 'Premium', [
			$this->capability('online_payments', 'features', 'bool', 'true'),
		]);
		$user = $this->userWithPlans($userPlan, $subscriptionPlan);
		$service = new PlanCapabilityService(new PlanPermission);

		$this->assertTrue($service->has($user, 'online_payments'));
	}

	/** @test */
	public function documented_static_and_container_feature_checks_still_work(): void
	{
		$user = $this->userWithPlan($this->plan(3, 'premium', 'Premium', [
			$this->capability('logo_upload', 'features', 'bool', 'true'),
			$this->capability('custom_branding', 'features', 'bool', 'true'),
			$this->capability('custom_prefix', 'features', 'bool', 'true'),
			$this->capability('online_payments', 'features', 'bool', 'true'),
			$this->capability('multi_currency', 'features', 'bool', 'true'),
			$this->capability('ai_invoice_assistant', 'features', 'bool', 'true'),
			$this->capability('automated_reminders', 'features', 'string', 'automatic'),
			$this->capability('pdf_watermark', 'features', 'bool', 'false'),
			$this->capability('email_watermark', 'features', 'bool', 'false'),
		]));

		$staticPermission = PlanPermission::attempt($user);
		$injectedPermission = (new PlanPermission)->forUser($user);

		foreach ([
			'upload business logo',
			'custom brand colors',
			'custom invoice numbering',
			'online payments',
			'multi currency',
			'ai invoice assistant',
			'automated reminders',
			'remove pdf watermark',
			'no pdf watermark',
			'remove email watermark',
			'no email watermark',
		] as $ability) {
			$this->assertTrue($staticPermission->can($ability), "Expected static check [{$ability}] to be allowed.");
			$this->assertTrue($injectedPermission->can($ability), "Expected injected check [{$ability}] to be allowed.");
		}
	}

	/** @test */
	public function documented_limit_checks_and_explicit_limit_usage_still_work(): void
	{
		$user = $this->userWithPlan($this->plan(2, 'pro', 'Pro', [
			$this->capability('max_business_profiles', 'limits', 'int', '3', 'businessProfiles'),
			$this->capability('max_clients', 'limits', 'int', '0', 'clients', ['unlimited' => true]),
			$this->capability('max_invoices_per_month', 'limits', 'int', '10', 'invoices', ['usage' => 'monthly']),
		]));

		$permission = PlanPermission::attempt($user);

		$this->assertTrue($permission->can('create invoice', 9));
		$this->assertFalse($permission->can('create invoice', 10));
		$this->assertTrue($permission->can('create client', 999));
		$this->assertTrue($permission->can('create business profile', 2));
		$this->assertFalse($permission->can('create business profile', 3));

		$this->assertTrue($permission->canWithinLimit('max_invoices_per_month', 9));
		$this->assertFalse($permission->canWithinLimit('max_invoices_per_month', 10));
		$this->assertTrue($permission->canWithinLimit('max_clients', 999));
		$this->assertTrue($permission->canWithinLimit('max_business_profiles', 2));
		$this->assertFalse($permission->canWithinLimit('max_business_profiles', 3));
	}

	/** @test */
	public function documented_raw_values_and_api_payload_shape_still_work(): void
	{
		$user = $this->userWithPlan($this->plan(2, 'pro', 'Pro', [
			$this->capability('max_business_profiles', 'limits', 'int', '3', 'businessProfiles'),
			$this->capability('max_clients', 'limits', 'int', '0', 'clients', ['unlimited' => true]),
			$this->capability('max_invoices_per_month', 'limits', 'int', '10', 'invoices', ['usage' => 'monthly']),
			$this->capability('logo_upload', 'features', 'bool', 'true'),
			$this->capability('templates_tier', 'features', 'string', 'multiple'),
			$this->capability('custom_branding', 'features', 'bool', 'true'),
		]));

		$permission = PlanPermission::attempt($user);

		$this->assertTrue($permission->get('logo_upload'));
		$this->assertSame('multiple', $permission->get('templates_tier'));
		$this->assertNull($permission->get('max_clients'));

		$result = $permission->toArray([
			'current:businessProfiles' => 1,
			'current:clients' => 4,
			'current:invoices' => 2,
		]);

		$this->assertSame([
			'id' => 2,
			'code' => 'pro',
			'name' => 'Pro',
		], $result['plan']);

		$this->assertSame(3, $result['limits']['max_business_profiles']);
		$this->assertNull($result['limits']['max_clients']);
		$this->assertSame(10, $result['limits']['max_invoices_per_month']);
		$this->assertSame(1, $result['limits']['current:businessProfiles']);
		$this->assertSame(4, $result['limits']['current:clients']);
		$this->assertSame(2, $result['limits']['current:invoices']);
		$this->assertTrue($result['flags']['logo_upload']);
		$this->assertSame('multiple', $result['flags']['templates_tier']);
		$this->assertTrue($result['allowed']['create:businessProfiles']);
		$this->assertTrue($result['allowed']['create:clients']);
		$this->assertTrue($result['allowed']['create:invoices']);
		$this->assertTrue($result['allowed']['logo_upload']);
		$this->assertFalse($result['not_allowed']['logo_upload']);
	}

	private function plan(int $id, string $code, string $name, array $capabilities = []): Plan
	{
		$plan = new Plan([
			'code' => $code,
			'name' => $name,
		]);
		$plan->id = $id;
		$plan->setRelation('capabilities', collect($capabilities));

		return $plan;
	}

	private function capability(
		string $key,
		string $group,
		string $type,
		string $value,
		?string $relationship = null,
		?array $meta = null
	): PlanCapability {
		return new PlanCapability([
			'key' => $key,
			'group' => $group,
			'type' => $type,
			'value' => $value,
			'model_relationship' => $relationship,
			'meta' => $meta,
		]);
	}

	private function userWithPlans(Plan $userPlan, Plan $subscriptionPlan): User
	{
		$subscription = new UserSubscription([
			'plan_id' => $subscriptionPlan->id,
			'plan_code' => $subscriptionPlan->code,
		]);
		$subscription->setRelation('plan', $subscriptionPlan);

		$user = new User(['plan_id' => $userPlan->id]);
		$user->setRelation('plan', $userPlan);
		$user->setRelation('subscription', $subscription);

		return $user;
	}

	private function userWithPlan(Plan $plan): User
	{
		$user = new User(['plan_id' => $plan->id]);
		$user->setRelation('plan', $plan);
		$user->setRelation('subscription', null);

		return $user;
	}
}
