<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Middleware;

use BilliftySDK\SharedResources\Modules\Billing\Support\PlanPermission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlanLimit
{
    public function __construct(
        protected PlanPermission $planPermission
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

        $permission = $this->planPermission->forUser($user);
        $relationName = $permission->relationship($limitKey) ?: $relationFromRoute;

        if (!$relationName || ! method_exists($user, $relationName)) {
            return response()->json([
                'message' => "Plan limit middleware misconfigured: relation [{$relationName}] not found on User.",
            ], 500);
        }

        $currentUsage = $permission->currentUsage($limitKey);

        if (! $permission->canWithinLimit($limitKey, $currentUsage)) {
            return response()->json([
                'message'       => 'You have reached the limit for this resource on your current plan.',
                'error_code'    => 'plan_limit_reached',
                'limit_key'     => $limitKey,
                'current_usage' => $currentUsage,
            ], 403);
        }

        return $next($request);
    }

}
