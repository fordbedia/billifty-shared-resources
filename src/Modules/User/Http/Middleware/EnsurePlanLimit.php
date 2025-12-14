<?php

namespace BilliftySDK\SharedResources\Modules\User\Http\Middleware;

use BilliftySDK\SharedResources\Modules\User\Service\PlanCapabilityService;
use BilliftySDK\SharedResources\Modules\User\Traits\Capabilities;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlanLimit
{
	use Capabilities;

    public function __construct(
        protected PlanCapabilityService $planCaps
    ) {}

	/**
	 * Usage example:
	 *   'plan.limit:max_business_profiles,businessProfiles'
	 *
	 * @param Request $request
	 * @param Closure $next
	 * @param string $param
	 * @return Response
	 */
    public function handle(Request $request, Closure $next, string $param): Response
    {
        $user = $request->user('api'); // or just $request->user() if default guard

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            return redirect()->guest(route('login')); // adjust if needed
        }

        // Parse "max_business_profiles,businessProfiles"
        [$limitKey, $relationFromRoute] = array_pad(
            explode(',', $param),
            2,
            null
        );

		$relationName = $this->relationships($limitKey) ?: $relationFromRoute;

        if (!$relationName || ! method_exists($user, $relationName)) {
            return response()->json([
                'message' => "Plan limit middleware misconfigured: relation [{$relationName}] not found on User.",
            ], 500);
        }

        $currentUsage = $this->currentUsageFor($user, $relationName, $limitKey);

        if (! $this->planCaps->canWithinLimit($user, $limitKey, $currentUsage)) {
            return response()->json([
                'message'       => 'You have reached the limit for this resource on your current plan.',
                'error_code'    => 'plan_limit_reached',
                'limit_key'     => $limitKey,
                'current_usage' => $currentUsage,
            ], 403);
        }

        return $next($request);
    }

	/**
     * Decide whether to count usage monthly or all-time.
     * - Monthly when capability meta says so: meta['usage'] === 'monthly'
     * - Fallback: treat max_invoices_per_month as monthly
     */
    protected function currentUsageFor($user, string $relationName, string $limitKey): int
    {
        $query = $user->{$relationName}();

        // Pull capability meta safely (ActiveScope is respected)
        $cap = $user->plan?->capabilities?->firstWhere('key', $limitKey);
        $usageMode = $cap->meta['usage'] ?? null;

        $isMonthly =
            $usageMode === 'monthly'
            || $limitKey === 'max_invoices_per_month'; // fallback for your known monthly limit

        if ($isMonthly) {
            return $query->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])->count();
        }

        return $query->count();
    }

}
