<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Services;

use BilliftySDK\SharedResources\Modules\Billing\Exceptions\PlanLimitExceededException;
use BilliftySDK\SharedResources\Modules\Billing\Models\PlanUsagePeriod;
use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\PlanCapability;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PlanUsageService
{
	protected const PERIOD_CAPABILITY_KEYS = [
		'max_invoices_per_month',
	];

	public function currentUsage(User $user, string $capabilityKey, ?UserSubscription $subscription = null): int
	{
		return (int) $this->ensureCurrentPeriod($user, $capabilityKey, $subscription)->used;
	}

	public function ensureCurrentPeriod(
		User $user,
		string $capabilityKey,
		?UserSubscription $subscription = null,
		bool $lockForUpdate = false
	): PlanUsagePeriod {
		return DB::transaction(function () use ($user, $capabilityKey, $subscription, $lockForUpdate) {
			return $this->resolveCurrentPeriod($user, $capabilityKey, $subscription, $lockForUpdate);
		});
	}

	/**
	 * @return array<int, PlanUsagePeriod>
	 */
	public function ensureCurrentPeriodsForUser(User $user, ?UserSubscription $subscription = null): array
	{
		return DB::transaction(function () use ($user, $subscription) {
			$user = $this->lockUser($user);
			$subscription = $this->resolveSubscription($user, $subscription);
			$plan = $this->resolvePlan($user, $subscription);

			if (! $plan) {
				return [];
			}

			return $this->periodCapabilities($plan)
				->map(fn (PlanCapability $capability) => $this->resolveCurrentPeriod(
					$user,
					$capability->key,
					$subscription,
					false
				))
				->values()
				->all();
		});
	}

	public function assertCanConsumeForUpdate(
		User $user,
		string $capabilityKey,
		int $amount = 1,
		?UserSubscription $subscription = null
	): PlanUsagePeriod {
		return DB::transaction(function () use ($user, $capabilityKey, $amount, $subscription) {
			$period = $this->resolveCurrentPeriod($user, $capabilityKey, $subscription, true);
			$limit = $this->resolvedLimit($user, $capabilityKey, $period->subscription);

			if ($limit !== null && ((int) $period->used + $amount) > $limit) {
				throw new PlanLimitExceededException($capabilityKey, (int) $period->used, $limit);
			}

			return $period;
		});
	}

	public function incrementUsage(PlanUsagePeriod $period, int $amount = 1): void
	{
		if ((int) $period->limit === 0) {
			return;
		}

		$period->forceFill([
			'used' => (int) $period->used + $amount,
		])->save();
	}

	public function isPeriodBasedCapability(?PlanCapability $capability, string $capabilityKey): bool
	{
		return in_array($capabilityKey, self::PERIOD_CAPABILITY_KEYS, true)
			|| ($capability?->meta['usage'] ?? null) === 'monthly';
	}

	protected function resolveCurrentPeriod(
		User $user,
		string $capabilityKey,
		?UserSubscription $subscription,
		bool $lockForUpdate
	): PlanUsagePeriod {
		$user = $this->lockUser($user);
		$subscription = $this->resolveSubscription($user, $subscription);
		$bounds = $this->periodBounds($user, $subscription);
		$limit = $this->resolvedLimit($user, $capabilityKey, $subscription);
		$limitSnapshot = $limit ?? 0;

		$query = PlanUsagePeriod::query()
			->where('user_id', $user->getKey())
			->where('user_subscription_id', $subscription->getKey())
			->where('capability_key', $capabilityKey)
			->where('period_start', $bounds['start']->toDateTimeString())
			->where('period_end', $bounds['end']->toDateTimeString());

		if ($lockForUpdate) {
			$query->lockForUpdate();
		}

		$period = $query->first();

		if (! $period) {
			return PlanUsagePeriod::create([
				'user_id' => $user->getKey(),
				'user_subscription_id' => $subscription->getKey(),
				'capability_key' => $capabilityKey,
				'used' => 0,
				'limit' => $limitSnapshot,
				'period_start' => $bounds['start'],
				'period_end' => $bounds['end'],
				'reset_strategy' => $bounds['strategy'],
			]);
		}

		if ((int) $period->limit !== $limitSnapshot || $period->reset_strategy !== $bounds['strategy']) {
			$period->forceFill([
				'limit' => $limitSnapshot,
				'reset_strategy' => $bounds['strategy'],
			])->save();
		}

		return $period;
	}

	protected function lockUser(User $user): User
	{
		if (! $user->getKey()) {
			throw new \RuntimeException('Plan usage periods require a persisted user.');
		}

		return User::query()
			->whereKey($user->getKey())
			->lockForUpdate()
			->firstOrFail();
	}

	protected function resolveSubscription(User $user, ?UserSubscription $subscription = null): UserSubscription
	{
		if ($subscription?->getKey()) {
			return UserSubscription::query()
				->whereKey($subscription->getKey())
				->lockForUpdate()
				->firstOrFail();
		}

		$subscription = UserSubscription::query()
			->where('user_id', $user->getKey())
			->orderByDesc('id')
			->lockForUpdate()
			->first();

		if ($subscription) {
			return $subscription;
		}

		$plan = $this->resolvePlan($user, null);

		return UserSubscription::create([
			'user_id' => $user->getKey(),
			'plan_id' => $plan?->getKey() ?? $user->plan_id,
			'plan_code' => $plan?->code ?? 'free',
			'billing_cycle' => 'monthly',
			'currency' => 'usd',
			'unit_amount' => 0,
			'status' => 'active',
		]);
	}

	protected function resolvePlan(User $user, ?UserSubscription $subscription): ?Plan
	{
		if ($subscription) {
			if (! $subscription->relationLoaded('plan')) {
				$subscription->load('plan.capabilities');
			} elseif ($subscription->plan && ! $subscription->plan->relationLoaded('capabilities')) {
				$subscription->plan->load('capabilities');
			}

			if ($subscription->plan) {
				return $subscription->plan;
			}
		}

		if (! $user->relationLoaded('plan')) {
			$user->load('plan.capabilities');
		} elseif ($user->plan && ! $user->plan->relationLoaded('capabilities')) {
			$user->plan->load('capabilities');
		}

		return $user->plan;
	}

	protected function resolvedLimit(User $user, string $capabilityKey, ?UserSubscription $subscription = null): ?int
	{
		$plan = $this->resolvePlan($user, $subscription);
		$capability = $plan?->capability($capabilityKey);

		if (! $capability) {
			return 0;
		}

		if (
			$capability->type === 'int'
			&& (int) $capability->cast_value === 0
			&& ($capability->meta['unlimited'] ?? false)
		) {
			return null;
		}

		return (int) $capability->cast_value;
	}

	protected function periodBounds(User $user, UserSubscription $subscription): array
	{
		$now = now()->copy()->setMicrosecond(0);
		$planCode = strtolower((string) ($subscription->plan_code ?: $this->resolvePlan($user, $subscription)?->code));
		$periodEnd = $subscription->renews_at?->copy()?->setMicrosecond(0);

		if ($planCode !== 'free' && $periodEnd && $periodEnd->gt($now)) {
			$periodStart = $subscription->starts_at?->copy()?->setMicrosecond(0)
				?? $this->derivePeriodStart($subscription, $periodEnd);

			if ($periodStart->lte($now)) {
				return [
					'start' => $periodStart,
					'end' => $periodEnd,
					'strategy' => 'subscription_cycle',
				];
			}
		}

		$periodStart = $now->copy()->startOfMonth();

		return [
			'start' => $periodStart,
			'end' => $periodStart->copy()->addMonth(),
			'strategy' => 'calendar_month',
		];
	}

	protected function derivePeriodStart(UserSubscription $subscription, Carbon $periodEnd): Carbon
	{
		if ($subscription->billing_cycle === 'yearly') {
			return $periodEnd->copy()->subYear();
		}

		return $periodEnd->copy()->subMonth();
	}

	protected function periodCapabilities(Plan $plan)
	{
		if (! $plan->relationLoaded('capabilities')) {
			$plan->load('capabilities');
		}

		return $plan->capabilities
			->filter(fn (PlanCapability $capability) => $capability->group === 'limits')
			->filter(fn (PlanCapability $capability) => $this->isPeriodBasedCapability($capability, $capability->key));
	}
}
