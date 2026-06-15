<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Console\Commands;

use BilliftySDK\SharedResources\Modules\Invoicing\Jobs\SendInvoicePaymentReminderJob;
use BilliftySDK\SharedResources\Modules\Invoicing\Services\Reminders\InvoicePaymentReminderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendDueInvoicePaymentReminders extends Command
{
	protected $signature = 'invoice-reminders:send-due {--limit=200 : Maximum due reminders to dispatch in one run}';
	protected $description = 'Dispatch queued jobs for due invoice payment reminders.';

	public function handle(InvoicePaymentReminderService $reminderService): int
	{
		$limit = max(1, (int) $this->option('limit'));
		$dispatched = 0;

		$reminderService->duePendingReminderQuery()
			->limit($limit)
			->get(['id'])
			->each(function ($reminder) use (&$dispatched): void {
				$cacheKey = "invoice-payment-reminder-job:{$reminder->id}";

				if (! Cache::add($cacheKey, true, now()->addMinutes(20))) {
					return;
				}

				SendInvoicePaymentReminderJob::dispatch((int) $reminder->id);
				$dispatched++;
			});

		Log::info('invoice-reminders.send-due.dispatched', [
			'count' => $dispatched,
			'limit' => $limit,
		]);

		$this->info("Dispatched {$dispatched} invoice payment reminder job(s).");

		return self::SUCCESS;
	}
}
