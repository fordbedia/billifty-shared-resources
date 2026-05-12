<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Models;


use Illuminate\Database\Eloquent\Model;

class PaymentRecord extends Model
{
    protected $table = 'payment_records';
	protected $guarded = [];

	protected $casts = [
		'data' => 'array',
	];
}
