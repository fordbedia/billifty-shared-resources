<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Services\Billing;

use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Contracts\SubscriptionResult;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Support\Arr;
use Stripe\Customer;
use Stripe\StripeClient;
use Stripe\Subscription;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

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
}
