<?php

namespace App\Http\Controllers {
	if (! class_exists(Controller::class)) {
		class Controller {}
	}
}

namespace Stripe {
	if (! class_exists(StripeClient::class)) {
		class StripeClient {}
	}

	if (! class_exists(Webhook::class)) {
		class Webhook
		{
			public static function generateTestHeaderString(array $opts): string
			{
				return 'test_signature';
			}

			public static function constructEvent(string $payload, ?string $sigHeader, string $secret): object
			{
				$event = json_decode($payload);

				if (! $event) {
					throw new \UnexpectedValueException('Invalid payload');
				}

				return $event;
			}
		}
	}
}

namespace Stripe\Exception {
	if (! class_exists(SignatureVerificationException::class)) {
		class SignatureVerificationException extends \Exception {}
	}
}

namespace BilliftySDK\SharedResources\Modules\Billing\Tests\Http\Controllers {

	use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
	use BilliftySDK\SharedResources\Modules\Billing\Http\Controllers\StripeWebhookController;
	use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
	use BilliftySDK\SharedResources\Modules\Invoicing\Services\Reminders\InvoicePaymentReminderService;
	use BilliftySDK\SharedResources\Modules\User\Models\Plan;
	use BilliftySDK\SharedResources\Modules\User\Models\User;
	use BilliftySDK\SharedResources\TestCase\Migrations\BaseTest;
	use Carbon\Carbon;
	use Illuminate\Contracts\Auth\Authenticatable;
	use Illuminate\Http\Request;
	use Stripe\StripeClient;
	use Stripe\Webhook;

	class StripeWebhookControllerTest extends BaseTest
	{
		/** @test */
		public function it_fills_renews_at_when_a_user_subscribes(): void
		{
			[$user, $plan] = $this->userAndPlan();
			$periodStart = Carbon::parse('2026-06-01 00:00:00', 'UTC');
			$periodEnd = Carbon::parse('2026-07-01 00:00:00', 'UTC');
			$subscription = $this->stripeSubscription([
				'id' => 'sub_active',
				'customer' => $user->stripe_customer_id,
				'status' => 'active',
				'current_period_start' => $periodStart->timestamp,
				'current_period_end' => $periodEnd->timestamp,
				'metadata' => (object) [
					'billifty_user_id' => (string) $user->id,
					'plan_code' => $plan->code,
					'billing_cycle' => 'monthly',
				],
			]);

			$response = $this->controller($subscription)->handle(
				$this->signedRequest('customer.subscription.created', $subscription),
				new FakeBillingPaymentGateway
			);

			$this->assertSame(200, $response->getStatusCode());
			$stored = UserSubscription::query()->where('user_id', $user->id)->firstOrFail();
			$this->assertSame('active', $stored->status);
			$this->assertSame($periodEnd->toDateTimeString(), $stored->renews_at?->utc()->toDateTimeString());
			$this->assertNull($stored->cancels_at);
			$this->assertNull($stored->canceled_at);
		}

		/** @test */
		public function it_schedules_cancellation_without_canceling_until_the_period_ends(): void
		{
			[$user, $plan] = $this->userAndPlan();
			$periodEnd = Carbon::parse('2026-07-01 00:00:00', 'UTC');
			UserSubscription::create([
				'user_id' => $user->id,
				'plan_id' => $plan->id,
				'plan_code' => $plan->code,
				'billing_cycle' => 'monthly',
				'stripe_customer_id' => $user->stripe_customer_id,
				'stripe_subscription_id' => 'sub_canceling',
				'currency' => 'usd',
				'unit_amount' => 9900,
				'status' => 'active',
				'renews_at' => $periodEnd,
			]);
			$subscription = $this->stripeSubscription([
				'id' => 'sub_canceling',
				'customer' => $user->stripe_customer_id,
				'status' => 'active',
				'current_period_end' => $periodEnd->timestamp,
				'cancel_at_period_end' => true,
				'metadata' => (object) [
					'billifty_user_id' => (string) $user->id,
					'plan_code' => $plan->code,
					'billing_cycle' => 'monthly',
				],
			]);

			$response = $this->controller($subscription)->handle(
				$this->signedRequest('customer.subscription.updated', $subscription),
				new FakeBillingPaymentGateway
			);

			$this->assertSame(200, $response->getStatusCode());
			$stored = UserSubscription::query()->where('user_id', $user->id)->firstOrFail();
			$this->assertSame('active', $stored->status);
			$this->assertNull($stored->renews_at);
			$this->assertSame($periodEnd->toDateTimeString(), $stored->cancels_at?->utc()->toDateTimeString());
			$this->assertNull($stored->canceled_at);
		}

		private function controller(object $subscription): StripeWebhookController
		{
			return new StripeWebhookController(
				new FakeStripeClient($subscription),
				\Mockery::mock(InvoicePaymentReminderService::class)
			);
		}

		private function signedRequest(string $eventType, object $subscription): Request
		{
			config([
				'services.stripe.webhook_secret' => 'whsec_test',
				'services.stripe.connect_webhook_secret' => null,
			]);

			$payload = json_encode([
				'id' => 'evt_' . str_replace('.', '_', $eventType) . '_' . uniqid(),
				'type' => $eventType,
				'livemode' => false,
				'data' => [
					'object' => json_decode(json_encode($subscription), true),
				],
			], JSON_UNESCAPED_SLASHES);

			return Request::create('/stripe/webhook', 'POST', [], [], [], [
				'HTTP_STRIPE_SIGNATURE' => Webhook::generateTestHeaderString([
					'payload' => $payload,
					'secret' => 'whsec_test',
				]),
			], $payload);
		}

		private function stripeSubscription(array $overrides = []): object
		{
			return new FakeStripeSubscription(array_replace([
				'id' => 'sub_test',
				'customer' => 'cus_test',
				'status' => 'active',
				'current_period_start' => now()->subMonth()->timestamp,
				'current_period_end' => now()->addMonth()->timestamp,
				'cancel_at_period_end' => false,
				'cancel_at' => null,
				'canceled_at' => null,
				'metadata' => (object) [],
				'items' => (object) [
					'data' => [
						(object) [
							'price' => (object) [
								'id' => 'price_test',
								'currency' => 'usd',
								'unit_amount' => 9900,
							],
						],
					],
				],
			], $overrides));
		}

		private function userAndPlan(): array
		{
			$plan = Plan::query()->where('code', 'pro')->first()
				?? Plan::create(['code' => 'pro', 'name' => 'Pro']);

			$user = User::create([
				'plan_id' => Plan::query()->where('code', 'free')->value('id') ?? $plan->id,
				'fname' => 'Billing',
				'lname' => 'User',
				'name' => 'Billing User',
				'email' => 'billing+' . uniqid() . '@example.test',
				'password' => bcrypt('password'),
			]);
			$user->forceFill(['stripe_customer_id' => 'cus_' . $user->id])->save();

			return [$user->refresh(), $plan];
		}
	}

	if (class_exists(StripeClient::class)) {
		class FakeStripeClient extends StripeClient
		{
			public function __construct(private readonly object $subscription) {}

			public function __get($name)
			{
				if ($name === 'subscriptions') {
					return new class($this->subscription) {
						public function __construct(private readonly object $subscription) {}

						public function retrieve(string $subscriptionId, array $params = []): object
						{
							return $this->subscription;
						}
					};
				}

				return parent::__get($name);
			}
		}
	}

	#[\AllowDynamicProperties]
	class FakeStripeSubscription
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

	class FakeBillingPaymentGateway implements PaymentGateway
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
		}
	}
}
