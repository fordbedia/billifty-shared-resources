<?php

namespace BilliftySDK\SharedResources\Modules\User\Service;

use BilliftySDK\SharedResources\Modules\User\Models\User;

class PlanCapabilityService
{
    /**
     * Get a raw capability value for a user.
     */
    public function get(User $user, string $key, mixed $default = null): mixed
    {
        $plan = $user->plan;
        if (! $plan) {
            return $default;
        }

        $cap = $plan->capabilities
            ->firstWhere('key', $key);

        return $cap ? $cap->cast_value : $default;
    }

    /**
     * Check if a simple boolean capability is enabled.
     */
    public function has(User $user, string $key): bool
    {
        $value = $this->get($user, $key, false);
        return (bool) $value;
    }

    /**
     * Check a numeric limit against a current usage.
     * Returns true if user is allowed to perform the action one more time.
     *
     * Example:
     *  ->canWithinLimit($user, 'max_invoices_per_month', $invoicesThisMonth)
     */
    public function canWithinLimit(User $user, string $limitKey, int $currentUsage): bool
    {
        $limit = $this->get($user, $limitKey, null);

        // null = unlimited
        if (is_null($limit)) {
            return true;
        }

        if (! is_int($limit)) {
            return false;
        }

        return $currentUsage < $limit;
    }
}