<?php

namespace BilliftySDK\SharedResources\Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = ['name', 'email', 'user_id', 'subject', 'message'];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
