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

		public function colorScheme()
		{
			return $this->belongsTo(ColorScheme::class, 'color_scheme_id');
		}

		public function template()
		{
			return $this->belongsTo(InvoiceTemplates::class, 'invoice_template_id');
		}

		public static function relationships(): array
		{
			return [
				'businessProfile',
				'client',
				'items',
				'colorScheme.colors',
				'template'
			];
		}
}
