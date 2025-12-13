<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Services\Billing;

use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\StripeClient;
use Stripe\Subscription;

class StripePaymentGateway implements PaymentGateway
{
    /**
     * Stripe PHP SDK client.
     */
    public function __construct(
        protected StripeClient $client,
    ) {
    }

    /**
     * Ensure the given user has a Stripe Customer and return the customer_id.
     */
    public function ensureCustomer(AuthenticatableContract $user): string
    {
        if (! empty($user->stripe_customer_id)) {
            return $user->stripe_customer_id;
        }

        /** @var Customer $customer */
        $customer = $this->client->customers->create([
            'email'    => $user->email,
            'name'     => $user->name ?? null,
            'metadata' => [
                'app_user_id' => $user->id,
            ],
        ]);

        $user->forceFill([
            'stripe_customer_id' => $customer->id,
        ])->save();

        return $customer->id;
    }

    /**
     * Resolve a Stripe Price ID from plan_code + billing_cycle.
     *
     * Backed by config/services.php (or DB if you prefer).
     */
    public function resolvePriceId(string $planCode, string $billingCycle): string
    {
        $priceId = config("services.stripe.prices.{$planCode}.{$billingCycle}");

        if (! $priceId) {
            throw new \RuntimeException("Stripe price not configured for {$planCode}.{$billingCycle}");
        }

        return $priceId;
    }

    /**
     * Create a default_incomplete subscription and expand latest_invoice.payment_intent.
     *
     * This lets the Payment Element confirm THAT payment intent.
     */
    public function createIncompleteSubscription(string $customerId, string $priceId): Subscription
    {
        /** @var Subscription $subscription */
        $subscription = $this->client->subscriptions->create([
            'customer' => $customerId,
            'items'    => [
                ['price' => $priceId],
            ],

            // Create subscription but leave it incomplete until we confirm the PaymentIntent
            'payment_behavior'  => 'default_incomplete',

            // Make sure Stripe knows it's an auto-charge subscription
            'collection_method' => 'charge_automatically',

            // 🔑 Tell Stripe which payment method types to support AND to save the card
            'payment_settings'  => [
                'payment_method_types'        => ['card'],       // <-- ensures card is allowed
                'save_default_payment_method' => 'on_subscription',
            ],

            // We want the first invoice + its PaymentIntent expanded in the response
            'expand' => ['latest_invoice.payment_intent'],
        ]);

        return $subscription;
    }

	/**
	 * Helper used during confirmation:
	 * - Retrieves subscription with expanded latest_invoice.payment_intent
	 * - If no payment_intent is attached, optionally falls back to a given payment_intent_id
	 *
	 * @return array{
	 *     subscription: \Stripe\Subscription,
	 *     latestInvoice: mixed,
	 *     paymentIntent: \Stripe\PaymentIntent|null
	 * }
	 * @throws ApiErrorException
	 */
    public function getSubscriptionWithPaymentIntent(
        string $subscriptionId,
        ?string $fallbackPaymentIntentId = null
    ): array {
        /** @var Subscription $subscription */
        $subscription = $this->client->subscriptions->retrieve($subscriptionId, [
            'expand' => [
                'items.data.price',
                'latest_invoice.payment_intent',
            ],
        ]);

        $latestInvoice = $subscription->latest_invoice ?? null;
        /** @var PaymentIntent|null $paymentIntent */
        $paymentIntent = $latestInvoice?->payment_intent ?? null;

        // If Stripe did not attach a payment_intent, but frontend gave us one,
        // try to retrieve it directly from Stripe.
        if (! $paymentIntent && ! empty($fallbackPaymentIntentId)) {
            try {
                $paymentIntent = $this->client->paymentIntents->retrieve($fallbackPaymentIntentId);
            } catch (\Throwable $e) {
                Log::error('StripePaymentGateway.getSubscriptionWithPaymentIntent: failed to retrieve payment_intent by id', [
                    'payment_intent_id' => $fallbackPaymentIntentId,
                    'exception'         => $e->getMessage(),
                ]);
            }
        }

        return [
            'subscription'   => $subscription,
            'latestInvoice'  => $latestInvoice,
            'paymentIntent'  => $paymentIntent,
        ];
    }
}
