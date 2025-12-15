<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Services\Billing;

use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Contracts\SubscriptionResult;
use BilliftySDK\SharedResources\Modules\Billing\DTO\SubscriptionRequest;
use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Repository\Contract\UserInterface;
use Illuminate\Support\Facades\DB;

final class CheckoutService
{
    public function __construct(
        private PaymentGateway $gateway,
        private PlanPriceResolver $prices,
		private UserInterface $userRepo
    ) {}

    public function listSavedCards(string $customerId): array
    {
        return $this->gateway->listCardPaymentMethods($customerId);
    }

    public function removeSavedCard(string $paymentMethodId): void
    {
        $this->gateway->detachPaymentMethod($paymentMethodId);
    }

    public function subscribe(SubscriptionRequest $dto): SubscriptionResult
	{
		return DB::transaction(function () use ($dto) {
			$user = $dto->user;

			$customerId = $this->gateway->ensureCustomer($user);
			$priceId    = $this->prices->resolve($dto->planCode, $dto->billingCycle);

			if ($dto->saveCard && $dto->paymentMethodId) {
				$this->gateway->attachPaymentMethod(
					$dto->paymentMethodId,
					$customerId,
					makeDefault: true
				);
			}

			/** @var SubscriptionResult $result */
			$result = $this->gateway->createSubscription(
				$customerId,
				$priceId,
				$dto->paymentMethodId
			);

			$stripeSub = $result->subscription; // e.g. \Stripe\Subscription

			// Normalize plan code
			$planCode = is_object($dto->planCode) && method_exists($dto->planCode, 'value')
				? $dto->planCode->value
				: $dto->planCode;

			$planId = Plan::where('code', $planCode)->value('id');

			if ($planId) {
				// Update user's plan_id
				$user->forceFill([
					'plan_id' => $planId,
				])->save();

				// Create / update subscription record
				UserSubscription::updateOrCreate(
					[
						'user_id'               => $user->id,
						'stripe_subscription_id'=> $stripeSub->id,
					],
					[
						'plan_id'         => $planId,
						'plan_code'       => $planCode,
						'billing_cycle'   => $dto->billingCycle,
						'stripe_customer_id' => $customerId,
						'currency'        => $stripeSub->items->data[0]->price->currency ?? 'usd',
						'unit_amount'     => $stripeSub->items->data[0]->price->unit_amount ?? 0,
						'status'          => $stripeSub->status,
						'starts_at'       => isset($stripeSub->current_period_start)
							? now()->createFromTimestamp($stripeSub->current_period_start)
							: null,
						'renews_at'       => isset($stripeSub->current_period_end)
							? now()->createFromTimestamp($stripeSub->current_period_end)
							: null,
						'raw_payload'     => $stripeSub->toArray(),
					]
				);
			}

			return $result;
		});
	}
}