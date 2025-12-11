<?php

namespace BilliftySDK\SharedResources\Modules\Billing\DTO;

use BilliftySDK\SharedResources\Modules\User\Models\User;

final class SubscriptionRequest
{
    public function __construct(
        public readonly User $user,
        public readonly string $planCode,        // pro | premium
        public readonly string $billingCycle,    // monthly | yearly
        public readonly string $paymentMethodId,
        public readonly bool $saveCard
    ) {}
}