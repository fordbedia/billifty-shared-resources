<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Services\Billing;

use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\Billing\Repositories\Interfaces\UserSubscriptionInterface;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use BilliftySDK\SharedResources\Modules\User\Repository\Contract\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubscriptionService
{
	public function __construct(
		protected Request $request,
		protected UserSubscriptionInterface $userSubscriptionRepository,
		protected UserInterface $userRepository
	) {}

	private function frontendUrl(string $path): string
	{
		$baseUrl = rtrim((string) (config('app.frontend_url') ?: config('app.url')), '/');
		$path = '/' . ltrim($path, '/');

		return $baseUrl . $path;
	}

	public function confirmSubscription(): bool
	{
		if ($this->userSubscriptionRepository->hasSubscribed()) {
			return true;
		}
		return false;
	}

	public function handleFreeSubscription(?string $plan = null, ?string $cycle = null)
	{
		if (! $plan && ! $cycle) {
			$plan = $this->request->query('plan_code');
			$cycle = $this->request->query('billing_cycle');
		}
		// Check if user is logged in, then subscribed to free plan
		// Otherwise, proceed to login ?plan=free&cycle=monthly
		if (Auth::check()) {
			if ($this->confirmSubscription()) {
				return [
					'url' => config('services.stripe.manage_subscriptions_url')
				];
			}
			$user = auth()->user();
			$this->freePlan($user);
			$id = Str::ulid()->toBase32();
			return [
				'url' => config('services.stripe.return_url') . "?session_id={$id}"
			];
		}

		return [
			'url' => $this->frontendUrl('/auth?plan_code=free&billing_cycle=monthly')
		];
	}

	protected function freePlan(User $user, string $planCode = 'free', string $cycle = 'monthly')
	{
		if ($planCode !== 'free') {
			throw new \RuntimeException('Free plan only.');
		}

		$freePlanId = Plan::whereCode($planCode)->pluck('id')->first();
		$this->userRepository->updatePlan($freePlanId);

		$this->userSubscriptionRepository->upsert([
			'user_id' => $user->id,
			'plan_id' => $freePlanId,
			'plan_code' => $planCode,
			'billing_cycle' => $cycle,
		]);
	}

	public function decodeCallback(Request $request): ?array
	{
		$allowedPlans  = ['free', 'pro', 'premium'];
		$allowedCycles = ['monthly', 'yearly'];

		// --------------------------------------------
		// 1) Resolve "next" from either:
		//    - state.next (Google OAuth)
		//    - ?next=... (SPA/self-registration)
		// --------------------------------------------
		$next = $request->query('next'); // works for self-registration/auth route

		$stateRaw = $request->query('state'); // works for google callback
		if ($stateRaw) {
			$decoded = base64_decode($stateRaw, true);
			if ($decoded !== false) {
				$state = json_decode($decoded, true) ?: [];
				if (!empty($state['next'])) {
					$next = $state['next'];
				}
			}
		}

		// Security: allow only internal relative paths
		if ($next && ! Str::startsWith($next, '/')) {
			$next = null;
		}

		// Default next path
		$nextPath = $next ?: '/app/invoices';

		// --------------------------------------------
		// 2) Try to get plan/cycle from direct query:
		//    ?plan_code=pro&billing_cycle=monthly
		// --------------------------------------------
		$plan  = $request->query('plan_code');
		$cycle = $request->query('billing_cycle');

		// --------------------------------------------
		// 3) If missing, parse them from the "next" URL:
		//    next=/app/invoice/pricing?plan_code=pro&billing_cycle=monthly
		// --------------------------------------------
		if ((!$plan || !$cycle) && $next) {
			$parts = parse_url($next);
			$query = $parts['query'] ?? '';
			$params = [];
			parse_str($query, $params);

			$plan  = $plan  ?: ($params['plan_code'] ?? null);
			$cycle = $cycle ?: ($params['billing_cycle'] ?? null);
		}

		// --------------------------------------------
		// 4) Validate allowlist
		// --------------------------------------------
		if (!in_array($plan, $allowedPlans, true)) {
			$plan = null;
		}
		if (!in_array($cycle, $allowedCycles, true)) {
			$cycle = null;
		}

		// If we need both but one is missing, return null selection
		return ($plan && $cycle)
			? [
				'plan_code' => $plan,
				'billing_cycle' => $cycle,
				'next_path' => $nextPath, // handy for redirects
			]
			: [
				'plan_code' => null,
				'billing_cycle' => null,
				'next_path' => $nextPath,
			];
	}

	public function handle(string $url, array $next): string
	{
		$nextPath = $next['next_path'] ?? '/app/invoices';
		$planCode = $next['plan_code'] ?? null;
		$billingCycle = $next['billing_cycle'] ?? null;

		if (!$planCode || !$billingCycle) {
			// If no subscription is set, Automatically subscribed him/her to FREE plan.
			$planCode = 'free';
			$result = $this->handleFreeSubscription($planCode, null);
			return $result['url'] ?? $this->frontendUrl($nextPath);
		}

		if ($planCode === 'free') {
			$result = $this->handleFreeSubscription($planCode, $billingCycle);
			return $result['url'] ?? $this->frontendUrl($nextPath);
		}

		return $this->frontendUrl(
			"/app/invoice/pricing?plan_code={$planCode}&billing_cycle={$billingCycle}&auto_checkout=true"
		);
	}

}
