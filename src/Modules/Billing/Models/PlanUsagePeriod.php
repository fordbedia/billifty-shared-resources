<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Models;

use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanUsagePeriod extends Model
{
	protected $fillable = [
		'user_id',
		'user_subscription_id',
		'capability_key',
		'used',
		'limit',
		'period_start',
		'period_end',
		'reset_strategy',
	];

	protected $casts = [
		'used' => 'integer',
		'limit' => 'integer',
		'period_start' => 'datetime',
		'period_end' => 'datetime',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function subscription(): BelongsTo
	{
		return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
	}
}
