<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Middleware;

use BilliftySDK\SharedResources\Modules\Billing\Support\PlanPermission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlanCapability
{
    public function __construct(
        protected PlanPermission $planPermission
    ) {}

    /**
     * Usage: ->middleware('plan.capability:online_payments')
     */
    public function handle(Request $request, Closure $next, string $capabilityKey): Response
    {
        $user = $request->user();

        if (! $user) {
            // Not authenticated. Let your auth middleware handle login redirection,
            // but for safety we can just abort here.
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            return redirect()->guest(route('login')); // adjust to your auth route
        }

        if (! $this->planPermission->forUser($user)->has($capabilityKey)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your current plan does not allow this action. Please upgrade.',
                    'error_code' => 'plan_capability_denied',
                    'required_capability' => $capabilityKey,
                ], 403);
            }

            // For web requests, you might redirect to pricing page instead:
            return redirect()->to('/app/invoice/pricing')
                ->with('error', 'Your current plan does not allow this action. Please upgrade.');
        }

        return $next($request);
    }
}
