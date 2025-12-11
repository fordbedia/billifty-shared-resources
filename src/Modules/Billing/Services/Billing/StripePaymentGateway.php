<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Services\Billing;

use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Contracts\SubscriptionResult;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Support\Arr;
use Stripe\StripeClient;
use Stripe\Subscription as StripeSubscription;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class StripePaymentGateway implements PaymentGateway
{
    public function __construct(
        protected StripeClient $stripe,
    ) {}

    public function ensureCustomer(AuthenticatableContract $user): string
    {
        if (! empty($user->stripe_customer_id)) {
            return $user->stripe_customer_id;
        }

        $customer = $this->stripe->customers->create([
            'email' => $user->email,
            'name'  => $user->name,
        ]);

        $user->stripe_customer_id = $customer->id;
        $user->save();

        return $customer->id;
    }

    public function resolvePriceId(string $planCode, string $billingCycle): string
    {
        return match ([$planCode, $billingCycle]) {
            ['pro', 'monthly']     => config('services.stripe.prices.pro_monthly'),
            ['pro', 'yearly']      => config('services.stripe.prices.pro_yearly'),
            ['premium', 'monthly'] => config('services.stripe.prices.premium_monthly'),
            ['premium', 'yearly']  => config('services.stripe.prices.premium_yearly'),
            default                => throw new \InvalidArgumentException('Invalid plan / billing cycle'),
        };
    }

    public function createIncompleteSubscription(string $customerId, string $priceId): StripeSubscription
    {
        return $this->stripe->subscriptions->create([
            'customer' => $customerId,
            'items' => [
                ['price' => $priceId],
            ],
            'payment_behavior' => 'default_incomplete',
            'payment_settings' => [
                'save_default_payment_method' => 'on_subscription', // always save – low maintenance
            ],
        ]);
    }
}
