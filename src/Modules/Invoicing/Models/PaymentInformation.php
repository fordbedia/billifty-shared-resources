<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;


use BilliftySDK\SharedResources\Modules\Invoicing\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;

class PaymentInformation extends Model
{
    protected $table = 'payment_information';
		protected $guarded = [];

		protected $casts = [
			'payment_method' => PaymentMethod::class
		];
}
