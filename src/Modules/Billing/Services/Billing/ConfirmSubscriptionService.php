<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Services\Billing;

use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Carbon\Carbon;
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

            $stripeData = $this->gateway->getSubscriptionWithPaymentIntent($subscriptionId, $paymentIntentId);

            $subscription  = $stripeData['subscription'];
            $latestInvoice = $stripeData['latestInvoice'];
            $paymentIntent = $stripeData['paymentIntent'];

            if (! $paymentIntent || $paymentIntent->status !== 'succeeded') {
                Log::warning('ConfirmSubscriptionService.payment_not_succeeded', [
                    'subscription_id'       => $subscription->id ?? null,
                    'subscription_status'   => $subscription->status ?? null,
                    'invoice_id'            => $latestInvoice->id ?? null,
                    'payment_intent_id'     => $paymentIntent->id ?? null,
                    'payment_intent_status' => $paymentIntent->status ?? null,
                ]);

                throw new \Illuminate\Validation\ValidationException(
                    validator: validator([], []),
                    response: response()->json([
                        'message'               => 'Payment for this subscription is not completed.',
                        'subscription_status'   => $subscription->status ?? null,
                        'payment_intent_status' => $paymentIntent->status ?? null,
                    ], 422)
                );
            }

            $planCodeNormalized = strtolower($planCode);
            $planId = Plan::where('code', $planCodeNormalized)->value('id');

            if (! $planId) {
                Log::error('ConfirmSubscriptionService.plan_not_found', [
                    'plan_code' => $planCodeNormalized,
                ]);

                throw new \RuntimeException('Plan not found for this subscription.');
            }

            $user->forceFill(['plan_id' => $planId])->save();

            $stripeItem  = $subscription->items->data[0] ?? null;
            $stripePrice = $stripeItem?->price ?? null;

			// Compute period start/end safely
			$subPeriodStart = $subscription->current_period_start ?? null;
			$subPeriodEnd   = $subscription->current_period_end ?? null;

			// Fallback: invoice line period (very reliable for first charge)
			$invPeriodStart = null;
			$invPeriodEnd   = null;

			if (is_object($latestInvoice) && isset($latestInvoice->lines) && isset($latestInvoice->lines->data[0]->period)) {
				$invPeriodStart = $latestInvoice->lines->data[0]->period->start ?? null;
				$invPeriodEnd   = $latestInvoice->lines->data[0]->period->end ?? null;
			}

			$periodStart = $subPeriodStart ?? $invPeriodStart;
			$periodEnd   = $subPeriodEnd ?? $invPeriodEnd;

			$startsAt = $periodStart ? Carbon::createFromTimestamp((int) $periodStart) : null;
			$renewsAt = $periodEnd   ? Carbon::createFromTimestamp((int) $periodEnd)   : null;


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
                    'starts_at'          => $startsAt,
                    'renews_at'          => $renewsAt,
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
