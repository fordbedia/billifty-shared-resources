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
}
