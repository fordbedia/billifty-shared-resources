<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Contracts;

use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Stripe\Subscription;

interface PaymentGateway
{
    public function ensureCustomer(AuthenticatableContract $user): string;

    public function resolvePriceId(string $planCode, string $billingCycle): string;

    public function createIncompleteSubscription(string $customerId, string $priceId): Subscription;
}