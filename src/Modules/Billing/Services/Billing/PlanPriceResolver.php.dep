<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Services\Billing;

final class PlanPriceResolver
{
    public function resolve(string $planCode, string $billingCycle): string
    {
        return match ($planCode.'_'.$billingCycle) {
            'pro_monthly'     => env('STRIPE_PRICE_PRO_MONTHLY'),
            'pro_yearly'      => env('STRIPE_PRICE_PRO_YEARLY'),
            'premium_monthly' => env('STRIPE_PRICE_PREMIUM_MONTHLY'),
            'premium_yearly'  => env('STRIPE_PRICE_PREMIUM_YEARLY'),
            default => throw new \InvalidArgumentException('Unknown plan/cycle'),
        };
    }
}