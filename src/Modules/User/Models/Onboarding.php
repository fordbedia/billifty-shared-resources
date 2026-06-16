<?php

namespace BilliftySDK\SharedResources\Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class Onboarding extends Model
{
    protected $table = 'onboarding';
	protected $guarded = [];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
