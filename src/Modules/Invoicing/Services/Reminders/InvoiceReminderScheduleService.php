<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Services\Reminders;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceReminderSchedule;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class InvoiceReminderScheduleService
{
	public const STANDARD_CODE = 'standard';

	private const STANDARD_RULES = [
		['offset_days' => -3, 'label' => '3 days before due date', 'sort_order' => 10],
		['offset_days' => 0, 'label' => 'On due date', 'sort_order' => 20],
		['offset_days' => 3, 'label' => '3 days after due date', 'sort_order' => 30],
		['offset_days' => 7, 'label' => '7 days after due date', 'sort_order' => 40],
	];

	public function resolve(?int $scheduleId = null): InvoiceReminderSchedule
	{
		if ($scheduleId) {
			$schedule = InvoiceReminderSchedule::query()
				->whereKey($scheduleId)
				->where('is_active', true)
				->first();

			if ($schedule) {
				return $schedule->loadMissing('rules');
			}
		}

		return $this->getOrCreateStandardSchedule();
	}

	public function getOrCreateStandardSchedule(): InvoiceReminderSchedule
	{
		$schedule = InvoiceReminderSchedule::query()
			->where('code', self::STANDARD_CODE)
			->where('type', 'system')
			->first();

		if (! $schedule) {
			$schedule = InvoiceReminderSchedule::query()->create([
				'code' => self::STANDARD_CODE,
				'name' => 'Standard',
				'type' => 'system',
				'is_active' => true,
			]);
		} elseif (! $schedule->is_active) {
			$schedule->forceFill(['is_active' => true])->save();
		}

		$this->ensureStandardScheduleRules($schedule);

		return $schedule->loadMissing('rules');
	}

	public function ensureStandardScheduleRules(InvoiceReminderSchedule $schedule): void
	{
		foreach (self::STANDARD_RULES as $rule) {
			$schedule->rules()->updateOrCreate(
				[
					'offset_days' => $rule['offset_days'],
					'channel' => 'email',
				],
				[
					'label' => $rule['label'],
					'sort_order' => $rule['sort_order'],
					'is_active' => true,
				]
			);
		}
	}

	public function scheduledAtForInvoice(Invoices $invoice, int $offsetDays): CarbonInterface
	{
		$timezone = config('app.timezone', 'UTC');

		return Carbon::parse($invoice->due_on, $timezone)
			->startOfDay()
			->addDays($offsetDays)
			->setTime(9, 0);
	}
}
