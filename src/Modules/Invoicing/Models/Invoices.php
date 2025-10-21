<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;


use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $table = 'invoices';
		protected $guarded = [];

		public function businessProfile()
		{
			return $this->belongsTo(BusinessProfiles::class, 'business_profile_id');
		}

		public function client()
		{
			return $this->belongsTo(Clients::class, 'client_id');
		}

		public function items()
		{
			return $this->hasMany(InvoiceItems::class, 'invoice_id', 'id');
		}
}
