<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Console\Commands;

use BilliftySDK\SharedResources\Modules\Billing\Services\PlanUsageService;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncPlanUsagePeriods extends Command
{
	protected $signature = 'billing:sync-plan-usage-periods {--chunk=200 : Users to process per chunk}';
	protected $description = 'Ensure current plan usage period rows exist for period-based plan capabilities.';

	public function handle(PlanUsageService $planUsageService): int
	{
		$chunk = max(1, (int) $this->option('chunk'));
		$processed = 0;
		$createdOrFound = 0;
		$failed = 0;

		User::query()
			->whereNotNull('plan_id')
			->with(['subscription.plan.capabilities', 'plan.capabilities'])
			->orderBy('id')
			->chunkById($chunk, function ($users) use ($planUsageService, &$processed, &$createdOrFound, &$failed) {
				foreach ($users as $user) {
					$processed++;

					try {
						$createdOrFound += count($planUsageService->ensureCurrentPeriodsForUser($user));
					} catch (\Throwable $e) {
						$failed++;

						Log::error('billing:sync-plan-usage-periods.failed', [
							'user_id' => $user->id,
							'err' => $e->getMessage(),
						]);
					}
				}
			});

		$this->info("Processed {$processed} users; ensured {$createdOrFound} usage periods; failures={$failed}.");

		return $failed > 0 ? self::FAILURE : self::SUCCESS;
	}
}
