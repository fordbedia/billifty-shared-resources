<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Support;

use InvalidArgumentException;
use RuntimeException;

final class StripePriceResolver
{
    private const SUPPORTED_PLANS = ['pro', 'premium'];
    private const SUPPORTED_CYCLES = ['monthly', 'yearly'];

    public function resolve(string $planCode, string $billingCycle): string
    {
        $planCode = strtolower(trim($planCode));
        $billingCycle = strtolower(trim($billingCycle));

        $this->assertSupported($planCode, $billingCycle);

        $priceId = config("services.stripe.prices.{$planCode}.{$billingCycle}");

        if (! is_string($priceId) || trim($priceId) === '') {
            throw new RuntimeException("Stripe price ID missing for {$planCode}.{$billingCycle}.");
        }

        return $priceId;
    }

    public function planAndCycleFromPriceId(string $priceId): ?array
    {
        $prices = config('services.stripe.prices', []);

        foreach (self::SUPPORTED_PLANS as $plan) {
            foreach (self::SUPPORTED_CYCLES as $cycle) {
                $configuredPriceId = $prices[$plan][$cycle] ?? null;

                if (is_string($configuredPriceId) && $configuredPriceId === $priceId) {
                    return ['plan_code' => $plan, 'billing_cycle' => $cycle];
                }
            }
        }

        return null;
    }

    private function assertSupported(string $planCode, string $billingCycle): void
    {
        if (! in_array($planCode, self::SUPPORTED_PLANS, true)) {
            throw new InvalidArgumentException("Unsupported Stripe plan code [{$planCode}].");
        }

        if (! in_array($billingCycle, self::SUPPORTED_CYCLES, true)) {
            throw new InvalidArgumentException("Unsupported Stripe billing cycle [{$billingCycle}].");
        }
    }
}
