<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceReminderScheduleRule extends Model
{
	protected $table = 'invoice_reminder_schedule_rules';
	protected $guarded = [];

	protected $casts = [
		'offset_days' => 'integer',
		'sort_order' => 'integer',
		'is_active' => 'boolean',
	];

	public function schedule()
	{
		return $this->belongsTo(InvoiceReminderSchedule::class, 'invoice_reminder_schedule_id');
	}
}
