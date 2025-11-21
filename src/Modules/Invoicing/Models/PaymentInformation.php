<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;


use BilliftySDK\SharedResources\Modules\Invoicing\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentInformation extends Model
{
	use softDeletes;

    protected $table = 'payment_information';

	protected $guarded = [];

	protected $casts = [
		'payment_method' => PaymentMethod::class
	];
}
