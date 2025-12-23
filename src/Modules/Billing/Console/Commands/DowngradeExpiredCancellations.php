<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;

class DowngradeExpiredCancellations extends Command
{
    protected $signature = 'billing:downgrade-expired-cancellations {--grace=1 : Days after cancels_at before downgrade}';
    protected $description = 'Downgrade users to Free when cancels_at is past the grace period.';

    public function handle(PaymentGateway $gateway): int
    {
        $graceDays = (int) $this->option('grace');
        $cutoff = Carbon::now()->subDays($graceDays);

        $subs = UserSubscription::query()
            ->whereNotNull('cancels_at')
            ->where('cancels_at', '<=', $cutoff)
            ->where(function ($q) {
                $q->whereNull('canceled_at')
                  ->orWhere('status', '!=', 'canceled');
            })
            ->whereNotNull('stripe_subscription_id') // still has stripe subscription reference
            ->limit(500)
            ->get();

        $this->info("Found {$subs->count()} subscriptions to downgrade (cutoff={$cutoff}).");

        foreach ($subs as $sub) {
            try {
                $gateway->markUserAsFree(
                    (int) $sub->user_id,
                    $sub->stripe_customer_id,
                    $sub->stripe_subscription_id,
                    null
                );

                $this->info("Downgraded user_id={$sub->user_id}");
            } catch (\Throwable $e) {
                Log::error('billing:downgrade-expired-cancellations.failed', [
                    'user_id' => $sub->user_id,
                    'err' => $e->getMessage(),
                ]);
            }
        }

        return self::SUCCESS;
    }
}
