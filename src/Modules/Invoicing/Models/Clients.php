<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clients extends Model
{
	use softDeletes;

    protected $table = 'clients';
		protected $guarded = [];
}
