<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Services\Billing;

use BilliftySDK\SharedResources\Modules\User\Models\User;
use Stripe\StripeClient;

class StripeCustomerService
{
    public function __construct(private StripeClient $stripe) {}

    public function ensureCustomer(User $user): string
    {
        if ($user->stripe_customer_id) return $user->stripe_customer_id;

        $customer = $this->stripe->customers->create([
            'email' => $user->email,
            'name'  => $user->name,
        ]);

        $user->forceFill(['stripe_customer_id' => $customer->id])->save();

        return $customer->id;
    }
}