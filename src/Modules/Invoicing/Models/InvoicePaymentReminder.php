<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePaymentReminder extends Model
{
	public const STATUS_PENDING = 'pending';
	public const STATUS_SENT = 'sent';
	public const STATUS_SKIPPED = 'skipped';
	public const STATUS_FAILED = 'failed';
	public const STATUS_CANCELLED = 'cancelled';

	protected $table = 'invoice_payment_reminders';
	protected $guarded = [];

	protected $casts = [
		'offset_days' => 'integer',
		'scheduled_at' => 'datetime',
		'sent_at' => 'datetime',
		'attempts' => 'integer',
	];

	public function invoice()
	{
		return $this->belongsTo(Invoices::class, 'invoice_id');
	}

	public function schedule()
	{
		return $this->belongsTo(InvoiceReminderSchedule::class, 'invoice_reminder_schedule_id');
	}
}
