<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessProfiles extends Model
{
	use softDeletes;

    protected $table = 'business_profiles';
	protected $guarded = [];


	public function paymentInformation()
	{
		return $this->hasOne(PaymentInformation::class, 'id', 'payment_information_id');
	}
}
