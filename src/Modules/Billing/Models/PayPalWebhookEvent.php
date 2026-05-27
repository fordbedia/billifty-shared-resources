<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Models;

use Illuminate\Database\Eloquent\Model;

class PayPalWebhookEvent extends Model
{
    protected $table = 'paypal_webhook_events';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
        'received_at' => 'datetime',
    ];
}
