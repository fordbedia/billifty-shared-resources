<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Models;


use Illuminate\Database\Eloquent\Model;

class StripeWebhookEvents extends Model
{
    protected $table = 'stripe_webhook_events';
	protected $guarded = [];

	protected $casts = [
		'livemode' => 'boolean',
		'received_at' => 'datetime',
	];
}
