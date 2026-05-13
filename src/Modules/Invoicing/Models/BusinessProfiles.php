<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessProfiles extends Model
{
	use softDeletes;

    protected $table = 'business_profiles';
	protected $guarded = [];

	public function paymentInformations()
	{
		return $this->hasMany(PaymentInformation::class, 'business_profile_id')->orderBy('id');
	}
}
