<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Services\Reminders;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoicePaymentReminder;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InvoicePaymentReminderService
{
	public function __construct(
		protected InvoiceReminderScheduleService $scheduleService,
	) {}

	public function enableReminders(Invoices $invoice, ?int $scheduleId = null): Invoices
	{
		$this->assertCanEnableReminders($invoice);

		return DB::transaction(function () use ($invoice, $scheduleId): Invoices {
			$lockedInvoice = Invoices::query()
				->whereKey($invoice->getKey())
				->lockForUpdate()
				->firstOrFail();

			$this->assertCanEnableReminders($lockedInvoice);

			$schedule = $this->scheduleService->resolve($scheduleId ?? $lockedInvoice->invoice_reminder_schedule_id);

			$lockedInvoice->forceFill([
				'payment_reminders_enabled' => true,
				'invoice_reminder_schedule_id' => $schedule->getKey(),
				'payment_reminders_completed_at' => null,
			])->save();

			$this->generateReminderRows($lockedInvoice, $schedule->getKey());

			return $lockedInvoice->refresh();
		});
	}

	public function disableReminders(Invoices $invoice): Invoices
	{
		return DB::transaction(function () use ($invoice): Invoices {
			$lockedInvoice = Invoices::query()
				->whereKey($invoice->getKey())
				->lockForUpdate()
				->firstOrFail();

			InvoicePaymentReminder::query()
				->where('invoice_id', $lockedInvoice->getKey())
				->where('status', InvoicePaymentReminder::STATUS_PENDING)
				->update([
					'status' => InvoicePaymentReminder::STATUS_CANCELLED,
					'sent_at' => null,
					'updated_at' => now(),
				]);

			$lockedInvoice->forceFill([
				'payment_reminders_enabled' => false,
			])->save();

			return $lockedInvoice->refresh();
		});
	}

	public function generateReminderRows(Invoices $invoice, ?int $scheduleId = null): void
	{
		$schedule = $this->scheduleService->resolve($scheduleId ?? $invoice->invoice_reminder_schedule_id);
		$now = now();

		foreach ($schedule->rules()->where('is_active', true)->get() as $rule) {
			$scheduledAt = $this->scheduleService->scheduledAtForInvoice($invoice, (int) $rule->offset_days);
			$isPast = $scheduledAt->lessThan($now);

			$reminder = InvoicePaymentReminder::query()
				->where('invoice_id', $invoice->getKey())
				->where('offset_days', (int) $rule->offset_days)
				->first();

			if ($reminder?->status === InvoicePaymentReminder::STATUS_SENT) {
				continue;
			}

			$payload = [
				'invoice_reminder_schedule_id' => $schedule->getKey(),
				'label' => $rule->label,
				'scheduled_at' => $scheduledAt,
				'sent_at' => null,
				'status' => $isPast
					? InvoicePaymentReminder::STATUS_SKIPPED
					: InvoicePaymentReminder::STATUS_PENDING,
				'last_error' => $isPast
					? 'Skipped because the scheduled reminder time was already in the past when reminders were generated.'
					: null,
			];

			if ($reminder) {
				$reminder->forceFill($payload)->save();
				continue;
			}

			InvoicePaymentReminder::query()->create($payload + [
				'invoice_id' => $invoice->getKey(),
				'offset_days' => (int) $rule->offset_days,
			]);
		}
	}

	public function duePendingReminderQuery(?CarbonInterface $now = null): Builder
	{
		return InvoicePaymentReminder::query()
			->where('status', InvoicePaymentReminder::STATUS_PENDING)
			->whereNotNull('scheduled_at')
			->where('scheduled_at', '<=', $now ?? now())
			->whereHas('invoice', function (Builder $query): void {
				$query
					->where('payment_reminders_enabled', true)
					->whereNull('paid_at')
					->where('status', '!=', 'paid')
					->where('amount_due_cents', '>', 0);
			})
			->orderBy('scheduled_at')
			->orderBy('id');
	}

	public function markSent(InvoicePaymentReminder $reminder): void
	{
		$reminder->forceFill([
			'status' => InvoicePaymentReminder::STATUS_SENT,
			'sent_at' => now(),
			'last_error' => null,
		])->save();

		$this->completeInvoiceIfNoPendingReminders($reminder->invoice);
	}

	public function markFailed(InvoicePaymentReminder $reminder, \Throwable|string $error): void
	{
		$reminder->forceFill([
			'status' => InvoicePaymentReminder::STATUS_FAILED,
			'last_error' => is_string($error) ? $error : $error->getMessage(),
		])->save();
	}

	public function skipPendingRemindersBecausePaid(Invoices $invoice): void
	{
		DB::transaction(function () use ($invoice): void {
			$lockedInvoice = Invoices::query()
				->whereKey($invoice->getKey())
				->lockForUpdate()
				->first();

			if (! $lockedInvoice) {
				return;
			}

			InvoicePaymentReminder::query()
				->where('invoice_id', $lockedInvoice->getKey())
				->where('status', InvoicePaymentReminder::STATUS_PENDING)
				->update([
					'status' => InvoicePaymentReminder::STATUS_SKIPPED,
					'sent_at' => null,
					'last_error' => 'Skipped because the invoice was paid.',
					'updated_at' => now(),
				]);

			$lockedInvoice->forceFill([
				'payment_reminders_completed_at' => now(),
			])->save();
		});
	}

	public function assertCanEnableReminders(Invoices $invoice): void
	{
		$invoice->loadMissing(['client']);

		$errors = [];

		if (! $invoice->due_on) {
			$errors['due_on'][] = 'A due date is required to enable automatic payment reminders.';
		}

		if (! $invoice->client?->email) {
			$errors['client_id'][] = 'A client email address is required to enable automatic payment reminders.';
		}

		if ($this->invoiceIsPaid($invoice)) {
			$errors['payment_reminders_enabled'][] = 'Automatic payment reminders cannot be enabled for a paid invoice.';
		}

		if ($errors) {
			throw ValidationException::withMessages($errors);
		}
	}

	public function invoiceIsPaid(Invoices $invoice): bool
	{
		return $invoice->status === 'paid'
			|| $invoice->paid_at !== null
			|| (int) $invoice->amount_due_cents <= 0;
	}

	protected function completeInvoiceIfNoPendingReminders(?Invoices $invoice): void
	{
		if (! $invoice) {
			return;
		}

		$hasPending = InvoicePaymentReminder::query()
			->where('invoice_id', $invoice->getKey())
			->where('status', InvoicePaymentReminder::STATUS_PENDING)
			->exists();

		if (! $hasPending) {
			$invoice->forceFill([
				'payment_reminders_completed_at' => Carbon::now(),
			])->save();
		}
	}
}
