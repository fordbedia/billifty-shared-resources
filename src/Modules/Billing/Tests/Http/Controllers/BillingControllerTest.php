<?php

namespace Stripe;

if (! class_exists(StripeClient::class)) {
	class StripeClient {}
}

namespace BilliftySDK\SharedResources\Modules\Billing\Tests\Http\Controllers;

use BilliftySDK\SharedResources\Modules\Billing\Http\Controllers\BillingController;
use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use BilliftySDK\SharedResources\TestCase\Migrations\BaseTest;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class BillingControllerTest extends BaseTest
{
	/** @test */
	public function cancel_my_subscription_sets_cancels_at_and_clears_renews_at_without_immediate_cancel(): void
	{
		[$user, $plan] = $this->userAndPlan();
		UserSubscription::create([
			'user_id' => $user->id,
			'plan_id' => $plan->id,
			'plan_code' => $plan->code,
			'billing_cycle' => 'monthly',
			'stripe_customer_id' => $user->stripe_customer_id,
			'stripe_subscription_id' => 'sub_cancel_controller',
			'currency' => 'usd',
			'unit_amount' => 9900,
			'status' => 'active',
			'renews_at' => Carbon::parse('2026-07-01 00:00:00', 'UTC'),
		]);
		$cancelsAt = Carbon::parse('2026-07-01 00:00:00', 'UTC');
		$stripe = new FakeBillingStripeClient(new FakeBillingStripeSubscription([
			'id' => 'sub_cancel_controller',
			'status' => 'active',
			'cancel_at' => $cancelsAt->timestamp,
			'current_period_end' => $cancelsAt->timestamp,
			'items' => (object) ['data' => []],
		]));

		$request = Request::create('/billing/cancel', 'POST');
		$request->setUserResolver(fn () => $user);

		$response = (new BillingController)->cancelMySubscription(
			$request,
			new FakeBillingControllerPaymentGateway,
			$stripe
		);

		$this->assertSame(200, $response->getStatusCode());
		$stored = UserSubscription::query()->where('user_id', $user->id)->firstOrFail();
		$this->assertSame('active', $stored->status);
		$this->assertNull($stored->renews_at);
		$this->assertSame($cancelsAt->toDateTimeString(), $stored->cancels_at?->utc()->toDateTimeString());
		$this->assertNull($stored->canceled_at);
	}

	private function userAndPlan(): array
	{
		$plan = Plan::query()->where('code', 'pro')->first()
			?? Plan::create(['code' => 'pro', 'name' => 'Pro']);

		$user = User::create([
			'plan_id' => $plan->id,
			'fname' => 'Cancel',
			'lname' => 'User',
			'name' => 'Cancel User',
			'email' => 'cancel+' . uniqid() . '@example.test',
			'password' => bcrypt('password'),
		]);
		$user->forceFill(['stripe_customer_id' => 'cus_cancel_' . $user->id])->save();

		return [$user->refresh(), $plan];
	}
}

if (class_exists(\Stripe\StripeClient::class)) {
	class FakeBillingStripeClient extends \Stripe\StripeClient
	{
		public function __construct(private readonly object $subscription) {}

		public function __get($name)
		{
			if ($name === 'subscriptions') {
				return new class($this->subscription) {
					public ?string $updatedSubscriptionId = null;

					public function __construct(private readonly object $subscription) {}

					public function update(string $subscriptionId, array $params = []): object
					{
						$this->updatedSubscriptionId = $subscriptionId;

						return $this->subscription;
					}
				};
			}

			return parent::__get($name);
		}
	}
}

#[\AllowDynamicProperties]
class FakeBillingStripeSubscription
{
	public function __construct(private readonly array $attributes)
	{
		foreach ($attributes as $key => $value) {
			$this->{$key} = $value;
		}
	}

	public function toArray(): array
	{
		return json_decode(json_encode($this->attributes), true);
	}
}

class FakeBillingControllerPaymentGateway implements PaymentGateway
{
	public function ensureCustomer(Authenticatable $user): string
	{
		return (string) $user->stripe_customer_id;
	}

	public function resolvePriceId(string $planCode, string $billingCycle): string
	{
		return 'price_test';
	}

	public function createCheckoutSessionUrl(
		string $customerId,
		string $priceId,
		string $successUrl,
		string $cancelUrl,
		array $metadata = []
	): array {
		return ['url' => 'https://checkout.test'];
	}

	public function createBillingPortalSession(string $customerId, string $returnUrl): array
	{
		return ['url' => 'https://portal.test'];
	}

	public function markUserAsFree(?int $userId, ?string $stripeCustomerId, ?string $stripeSubscriptionId, ?string $payloadJson = null): void {}
}
