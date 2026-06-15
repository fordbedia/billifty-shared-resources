<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceReminderSchedule extends Model
{
	protected $table = 'invoice_reminder_schedules';
	protected $guarded = [];

	protected $casts = [
		'is_active' => 'boolean',
	];

	public function rules()
	{
		return $this->hasMany(InvoiceReminderScheduleRule::class, 'invoice_reminder_schedule_id')
			->orderBy('sort_order')
			->orderBy('offset_days');
	}

	public function invoices()
	{
		return $this->hasMany(Invoices::class, 'invoice_reminder_schedule_id');
	}
}
