<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Jobs;

use BilliftySDK\SharedResources\Modules\Invoicing\Mail\InvoicePaymentReminderMail;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoicePaymentReminder;
use BilliftySDK\SharedResources\Modules\Invoicing\Services\Reminders\InvoicePaymentReminderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendInvoicePaymentReminderJob implements ShouldQueue
{
	use Queueable;

	public int $tries = 1;

	public function __construct(
		public int $reminderId,
	) {}

	public function handle(InvoicePaymentReminderService $reminderService): void
	{
		try {
			DB::transaction(function () use ($reminderService): void {
				$reminder = InvoicePaymentReminder::query()
					->whereKey($this->reminderId)
					->lockForUpdate()
					->first();

				if (! $reminder || $reminder->status !== InvoicePaymentReminder::STATUS_PENDING) {
					return;
				}

				$invoice = $reminder->invoice()
					->with(['client', 'businessProfile', 'currency', 'paymentLink'])
					->lockForUpdate()
					->first();

				if (! $invoice) {
					$reminderService->markFailed($reminder, 'Invoice not found for reminder.');
					return;
				}

				if ($reminderService->invoiceIsPaid($invoice)) {
					$reminderService->skipPendingRemindersBecausePaid($invoice);
					return;
				}

				if (! $invoice->payment_reminders_enabled) {
					$reminder->forceFill([
						'status' => InvoicePaymentReminder::STATUS_CANCELLED,
						'sent_at' => null,
						'last_error' => 'Reminder cancelled because automatic payment reminders are disabled for this invoice.',
					])->save();
					return;
				}

				if (! $invoice->client?->email) {
					$reminderService->markFailed($reminder, 'Client email address is missing.');
					return;
				}

				$reminder->forceFill([
					'attempts' => ((int) $reminder->attempts) + 1,
					'last_error' => null,
				])->save();

				Mail::to($invoice->client->email)->send(
					new InvoicePaymentReminderMail($invoice, $reminder, $this->publicInvoiceUrl($invoice))
				);

				$reminderService->markSent($reminder);
			});
		} catch (Throwable $exception) {
			$this->markReminderFailed($exception);
		} finally {
			Cache::forget($this->cacheKey());
		}
	}

	protected function markReminderFailed(Throwable $exception): void
	{
		DB::transaction(function () use ($exception): void {
			$reminder = InvoicePaymentReminder::query()
				->whereKey($this->reminderId)
				->lockForUpdate()
				->first();

			if (! $reminder || $reminder->status !== InvoicePaymentReminder::STATUS_PENDING) {
				return;
			}

			$reminder->forceFill([
				'status' => InvoicePaymentReminder::STATUS_FAILED,
				'attempts' => ((int) $reminder->attempts) + 1,
				'last_error' => $exception->getMessage(),
			])->save();
		});
	}

	protected function publicInvoiceUrl($invoice): ?string
	{
		$token = $invoice->paymentLink?->token;

		return $token ? route('invoice.preview.link', ['token' => $token]) : null;
	}

	protected function cacheKey(): string
	{
		return "invoice-payment-reminder-job:{$this->reminderId}";
	}
}
