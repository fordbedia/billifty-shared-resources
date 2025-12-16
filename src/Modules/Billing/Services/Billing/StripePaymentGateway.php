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
    public function __construct(protected StripeClient $client) {}

    public function ensureCustomer(AuthenticatableContract $user): string
    {
        if ($user->stripe_customer_id) {
            return $user->stripe_customer_id;
        }

        /** @var Customer $customer */
        $customer = $this->client->customers->create([
            'email'    => $user->email,
            'name'     => $user->name ?? null,
            'metadata' => [
                'billifty_user_id' => (string) $user->id,
            ],
        ]);

        $user->forceFill([
            'stripe_customer_id' => $customer->id,
        ])->save();

        return $customer->id;
    }

    public function resolvePriceId(string $planCode, string $billingCycle): string
    {
        $priceId = config("services.stripe.prices.{$planCode}.{$billingCycle}");

        if (! $priceId) {
            throw new \RuntimeException("Stripe price not configured for {$planCode}.{$billingCycle}");
        }

        return $priceId;
    }

    /**
     * Create (or recover) an incomplete subscription using Stripe idempotency.
     *
     * @throws ApiErrorException
     */
    public function createIncompleteSubscription(
        string $customerId,
        string $priceId,
        array $metadata = []
    ): Subscription {
        $params = [
            'customer' => $customerId,
            'items'    => [['price' => $priceId]],
            'payment_behavior'  => 'default_incomplete',
            'collection_method' => 'charge_automatically',
            'payment_settings'  => [
                'payment_method_types'        => ['card'],
                'save_default_payment_method' => 'on_subscription',
            ],
            'metadata' => $metadata,
            'expand'   => ['latest_invoice.payment_intent'],
        ];

        // ✅ deterministic + stable per (customer, price, plan, cycle)
        $rawKey = implode('|', [
            'sub_intent:v2',
            $customerId,
            $priceId,
            (string) ($metadata['plan_code'] ?? ''),
            (string) ($metadata['billing_cycle'] ?? ''),
        ]);

        $idempotencyKey = 'sub_intent:' . hash('sha256', $rawKey);

        try {
            return $this->client->subscriptions->create(
                $params,
                ['idempotency_key' => $idempotencyKey]
            );
        } catch (ApiErrorException $e) {
            // Stripe sometimes returns 409 with wording like "in-progress request using this Idempotent Key"
            $isInProgress = ($e->getHttpStatus() === 409) && str_contains($e->getMessage(), 'Idempotent Key');

            if (! $isInProgress) {
                throw $e;
            }

            Log::warning('Stripe idempotency in progress — attempting recovery', [
                'customer' => $customerId,
                'price'    => $priceId,
                'key'      => $idempotencyKey,
            ]);

            // ✅ Quick retries to allow Stripe to finish creating the subscription
            $attempts = 6;                 // ~ (0.15 + 0.25 + 0.35 + 0.5 + 0.7 + 0.9) seconds total
            $delaysMs = [150, 250, 350, 500, 700, 900];

            for ($i = 0; $i < $attempts; $i++) {
                usleep($delaysMs[$i] * 1000);

                $found = $this->findLatestSubscriptionForCustomerAndPrice($customerId, $priceId);

                if ($found) {
                    Log::info('Stripe recovery succeeded', [
                        'subscription_id' => $found->id,
                        'customer'        => $customerId,
                        'price'           => $priceId,
                    ]);

                    // Make sure invoice/pi is expanded like the normal create call
                    return $this->client->subscriptions->retrieve($found->id, [
                        'expand' => ['latest_invoice.payment_intent'],
                    ]);
                }
            }

            // If we get here, it truly wasn't visible yet (or created with a different price)
            throw new \RuntimeException('Idempotent subscription recovery failed (not found after retries).');
        }
    }

    /**
     * Try to locate an existing subscription for the same customer + price.
     * Returns null if not found.
     */
    private function findLatestSubscriptionForCustomerAndPrice(string $customerId, string $priceId): ?Subscription
    {
        // We search across "open-ish" statuses because Stripe can briefly transition.
        $statuses = ['incomplete', 'trialing', 'active', 'past_due', 'unpaid'];

        foreach ($statuses as $status) {
            $subs = $this->client->subscriptions->all([
                'customer' => $customerId,
                'status'   => $status,
                'limit'    => 10,
                'expand'   => [
                    'data.items.data.price',
                    'data.latest_invoice.payment_intent',
                ],
            ]);

            foreach ($subs->data as $sub) {
                if (!isset($sub->items->data)) {
                    continue;
                }

                foreach ($sub->items->data as $item) {
                    // price can be object (expanded) or string
                    $itemPriceId = is_object($item->price) ? ($item->price->id ?? null) : $item->price;

                    if ($itemPriceId === $priceId) {
                        return $sub;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Used by ConfirmSubscriptionService
     *
     * @return array{
     *   subscription: \Stripe\Subscription,
     *   latestInvoice: mixed,
     *   paymentIntent: \Stripe\PaymentIntent|null
     * }
     */
    public function getSubscriptionWithPaymentIntent(
        string $subscriptionId,
        ?string $fallbackPaymentIntentId = null
    ): array {
        $subscription = $this->client->subscriptions->retrieve($subscriptionId, [
            'expand' => ['latest_invoice.payment_intent'],
        ]);

        $latestInvoice = $subscription->latest_invoice ?? null;
        $paymentIntent = null;

        if (is_object($latestInvoice)) {
            $paymentIntent = $latestInvoice->payment_intent ?? null;

            if (is_string($paymentIntent)) {
                $paymentIntent = $this->client->paymentIntents->retrieve($paymentIntent);
            }
        }

        if (! $paymentIntent && $fallbackPaymentIntentId) {
            /** @var PaymentIntent $pi */
            $pi = $this->client->paymentIntents->retrieve($fallbackPaymentIntentId);
            $paymentIntent = $pi;
        }

        return compact('subscription', 'latestInvoice', 'paymentIntent');
    }

	public function changeSubscriptionPrice(
        string $subscriptionId,
        string $priceId,
        string $prorationBehavior = 'create_prorations'
    ): Subscription {
        /** @var Subscription $subscription */
        $subscription = $this->client->subscriptions->retrieve($subscriptionId, [
            'expand' => ['items.data.price'],
        ]);

        $firstItem = $subscription->items->data[0] ?? null;

        if (! $firstItem) {
            throw new \RuntimeException('Subscription has no items to update.');
        }

        /** @var Subscription $updated */
        $updated = $this->client->subscriptions->update($subscriptionId, [
            'items' => [
                [
                    'id'    => $firstItem->id,
                    'price' => $priceId,
                ],
            ],
            'proration_behavior' => $prorationBehavior, // 'create_prorations' or 'none'
            'expand' => ['latest_invoice.payment_intent'],
        ]);

        Log::info('StripePaymentGateway.changeSubscriptionPrice', [
            'subscription_id' => $subscriptionId,
            'new_price_id'    => $priceId,
            'proration'       => $prorationBehavior,
        ]);

        return $updated;
    }

    public function cancelSubscription(
        string $subscriptionId,
        bool $atPeriodEnd = true
    ): Subscription {
        /** @var Subscription $updated */
        $updated = $this->client->subscriptions->update($subscriptionId, [
            'cancel_at_period_end' => $atPeriodEnd,
        ]);

        Log::info('StripePaymentGateway.cancelSubscription', [
            'subscription_id' => $subscriptionId,
            'at_period_end'   => $atPeriodEnd,
        ]);

        return $updated;
    }

    public function createCustomerSetupIntent(
        string $customerId,
        array $metadata = []
    ): \Stripe\SetupIntent
	{
        /** @var SetupIntent $setupIntent */
        $setupIntent = $this->client->setupIntents->create([
            'customer' => $customerId,
            // Let Stripe show card, wallets, etc.
            'automatic_payment_methods' => ['enabled' => true],
            'metadata'                  => $metadata,
        ]);

        Log::info('StripePaymentGateway.createCustomerSetupIntent', [
            'customer_id'    => $customerId,
            'setup_intent_id'=> $setupIntent->id,
        ]);

        return $setupIntent;
    }

    public function updateDefaultPaymentMethodForCustomerAndSubscription(
        string $customerId,
        ?string $subscriptionId,
        string $paymentMethodId
    ): void {
        // 1) Attach to customer (if not already)
        $this->client->paymentMethods->attach($paymentMethodId, [
            'customer' => $customerId,
        ]);

        // 2) Set as default for invoice settings
        $this->client->customers->update($customerId, [
            'invoice_settings' => [
                'default_payment_method' => $paymentMethodId,
            ],
        ]);

        // 3) Optionally set as default for subscription
        if ($subscriptionId) {
            $this->client->subscriptions->update($subscriptionId, [
                'default_payment_method' => $paymentMethodId,
            ]);
        }

        Log::info('StripePaymentGateway.updateDefaultPaymentMethodForCustomerAndSubscription', [
            'customer_id'      => $customerId,
            'subscription_id'  => $subscriptionId,
            'payment_method_id'=> $paymentMethodId,
        ]);
    }
}
