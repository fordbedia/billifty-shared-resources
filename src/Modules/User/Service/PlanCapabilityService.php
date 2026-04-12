<?php

namespace BilliftySDK\SharedResources\Modules\User\Service;

use BilliftySDK\SharedResources\Modules\Billing\Support\PlanPermission;
use BilliftySDK\SharedResources\Modules\User\Models\User;

class PlanCapabilityService
{
    public function __construct(
        protected PlanPermission $planPermission
    ) {}

    /**
     * Get a raw capability value for a user.
     */
    public function get(User $user, string $key, mixed $default = null): mixed
    {
        return $this->planPermission->forUser($user)->get($key, $default);
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
        return $this->planPermission->forUser($user)->canWithinLimit($limitKey, $currentUsage);
    }
}
