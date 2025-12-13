<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Services\Billing;

use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ConfirmSubscriptionService
{
    public function __construct(
        private StripePaymentGateway $gateway
    ) {}

    /**
     * Confirms that the Stripe subscription has a successful PaymentIntent,
     * then persists user.plan_id + user_subscriptions.
     *
     * @return array{status:string, plan_code:string, billing_cycle:string, subscription: \BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription}
     */
    public function handle(
        User $user,
        string $subscriptionId,
        string $planCode,
        string $billingCycle,
        ?string $paymentIntentId = null
    ): array {
        return DB::transaction(function () use ($user, $subscriptionId, $planCode, $billingCycle, $paymentIntentId) {
            Log::info('ConfirmSubscriptionService.start', [
                'user_id'           => $user->id,
                'subscription_id'   => $subscriptionId,
                'plan_code'         => $planCode,
                'billing_cycle'     => $billingCycle,
                'payment_intent_id' => $paymentIntentId,
            ]);

            // 1) Stripe retrieval + PI fallback (Stripe-only logic lives in gateway)
            $stripeData = $this->gateway->getSubscriptionWithPaymentIntent($subscriptionId, $paymentIntentId);

            $subscription  = $stripeData['subscription'];
            $latestInvoice = $stripeData['latestInvoice'];
            $paymentIntent = $stripeData['paymentIntent'];

            // 2) Guard: must be succeeded
            if (! $paymentIntent || $paymentIntent->status !== 'succeeded') {
                Log::warning('ConfirmSubscriptionService.payment_not_succeeded', [
                    'subscription_id'       => $subscription->id ?? null,
                    'subscription_status'   => $subscription->status ?? null,
                    'invoice_id'            => $latestInvoice->id ?? null,
                    'payment_intent_id'     => $paymentIntent->id ?? null,
                    'payment_intent_status' => $paymentIntent->status ?? null,
                ]);

                // Throwing keeps controller clean; Laravel will return 422 with our message
                throw new \Illuminate\Validation\ValidationException(
                    validator: validator([], []),
                    response: response()->json([
                        'message'               => 'Payment for this subscription is not completed.',
                        'subscription_status'   => $subscription->status ?? null,
                        'payment_intent_status' => $paymentIntent->status ?? null,
                    ], 422)
                );
            }

            // 3) Resolve plan_id
            $planCodeNormalized = strtolower($planCode);
            $planId = Plan::where('code', $planCodeNormalized)->value('id');

            if (! $planId) {
                Log::error('ConfirmSubscriptionService.plan_not_found', [
                    'plan_code' => $planCodeNormalized,
                ]);

                throw new \RuntimeException('Plan not found for this subscription.');
            }

            // 4) Persist: user.plan_id
            $user->forceFill(['plan_id' => $planId])->save();

            // 5) Persist: user_subscriptions
            $stripeItem  = $subscription->items->data[0] ?? null;
            $stripePrice = $stripeItem?->price ?? null;

            $subscriptionModel = UserSubscription::updateOrCreate(
                [
                    'user_id'                => $user->id,
                    'stripe_subscription_id' => $subscription->id,
                ],
                [
                    'plan_id'            => $planId,
                    'plan_code'          => $planCodeNormalized,
                    'billing_cycle'      => $billingCycle,
                    'stripe_customer_id' => $subscription->customer,
                    'currency'           => $stripePrice->currency ?? 'usd',
                    'unit_amount'        => $stripePrice->unit_amount ?? 0,
                    'status'             => $subscription->status,
                    'starts_at'          => isset($subscription->current_period_start)
                        ? now()->createFromTimestamp($subscription->current_period_start)
                        : null,
                    'renews_at'          => isset($subscription->current_period_end)
                        ? now()->createFromTimestamp($subscription->current_period_end)
                        : null,
                    'raw_payload'        => $subscription->toArray(),
                ]
            );

            Log::info('ConfirmSubscriptionService.success', [
                'user_id'              => $user->id,
                'user_subscription_id' => $subscriptionModel->id,
            ]);

            return [
                'status'        => 'ok',
                'plan_code'     => $planCodeNormalized,
                'billing_cycle' => $billingCycle,
                'subscription'  => $subscriptionModel,
            ];
        });
    }
}
