<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Models;


use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
	protected $fillable = [
        'user_id',
        'plan_id',
        'plan_code',
        'billing_cycle',
        'stripe_customer_id',
        'stripe_subscription_id',
        'currency',
        'unit_amount',
        'status',
        'starts_at',
        'renews_at',
        'cancels_at',
        'canceled_at',
        'raw_payload',
    ];

    protected $casts = [
        'starts_at'   => 'datetime',
        'renews_at'   => 'datetime',
        'cancels_at'  => 'datetime',
        'canceled_at' => 'datetime',
        'raw_payload' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
