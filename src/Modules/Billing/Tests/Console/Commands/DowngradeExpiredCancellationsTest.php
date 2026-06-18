<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Tests\Console\Commands;

use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use BilliftySDK\SharedResources\TestCase\Migrations\BaseTest;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;

class DowngradeExpiredCancellationsTest extends BaseTest
{
	/** @test */
	public function it_marks_expired_scheduled_cancellations_as_canceled_and_clears_cancels_at(): void
	{
		$free = Plan::query()->where('code', 'free')->first()
			?? Plan::create(['code' => 'free', 'name' => 'Free']);
		$pro = Plan::query()->where('code', 'pro')->first()
			?? Plan::create(['code' => 'pro', 'name' => 'Pro']);
		$user = User::create([
			'plan_id' => $pro->id,
			'fname' => 'Expired',
			'lname' => 'Subscriber',
			'name' => 'Expired Subscriber',
			'email' => 'expired+' . uniqid() . '@example.test',
			'password' => bcrypt('password'),
		]);
		$user->forceFill(['stripe_customer_id' => 'cus_expired_' . $user->id])->save();
		UserSubscription::create([
			'user_id' => $user->id,
			'plan_id' => $pro->id,
			'plan_code' => 'pro',
			'billing_cycle' => 'monthly',
			'stripe_customer_id' => $user->stripe_customer_id,
			'stripe_subscription_id' => 'sub_expired',
			'currency' => 'usd',
			'unit_amount' => 9900,
			'status' => 'active',
			'renews_at' => null,
			'cancels_at' => Carbon::now()->subDays(2),
			'canceled_at' => null,
		]);
		$this->app->instance(PaymentGateway::class, new ExpiredCancellationGateway);

		$this->artisan('billing:downgrade-expired-cancellations', ['--grace' => 1])
			->assertExitCode(0);

		$stored = UserSubscription::query()->where('user_id', $user->id)->firstOrFail();
		$this->assertSame($free->id, $stored->plan_id);
		$this->assertSame('free', $stored->plan_code);
		$this->assertSame('canceled', $stored->status);
		$this->assertNull($stored->renews_at);
		$this->assertNull($stored->cancels_at);
		$this->assertNotNull($stored->canceled_at);
		$this->assertSame($free->id, $user->refresh()->plan_id);
	}
}

class ExpiredCancellationGateway implements PaymentGateway
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

	public function markUserAsFree(?int $userId, ?string $stripeCustomerId, ?string $stripeSubscriptionId, ?string $payloadJson = null): void
	{
		$freeId = Plan::query()->where('code', 'free')->value('id');

		UserSubscription::query()
			->where('user_id', $userId)
			->update([
				'plan_id' => $freeId,
				'plan_code' => 'free',
				'billing_cycle' => 'monthly',
				'stripe_customer_id' => null,
				'stripe_subscription_id' => null,
				'status' => 'canceled',
				'renews_at' => null,
				'cancels_at' => null,
				'canceled_at' => now(),
			]);

		User::query()->whereKey($userId)->update(['plan_id' => $freeId]);
	}
}
