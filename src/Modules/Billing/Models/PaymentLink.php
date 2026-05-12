<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Models;


use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Illuminate\Database\Eloquent\Model;

class PaymentLink extends Model
{
    protected $table = 'payment_link';
    protected $guarded = [];

	public function invoice()
	{
		return $this->belongsTo(Invoices::class, 'invoice_id');
	}

	public static function relationships()
	{
		return [
			'invoice',
			'invoice.client',
			'invoice.businessProfile',
			'invoice.currency'
		];
	}

}
