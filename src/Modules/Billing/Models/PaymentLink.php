<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Models;


use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Illuminate\Database\Eloquent\Model;

class PaymentLink extends Model
{
    protected $table = 'payment_link';
    protected $guarded = [];
	public $appends = ['payment_methods'];

	public function invoice()
	{
		return $this->belongsTo(Invoices::class, 'invoice_id');
	}

	public static function relationships()
	{
		return [
			'invoice',
			'invoice.client',
			'invoice.businessProfile.paymentInformations',
			'invoice.currency'
		];
	}

	public function getPaymentMethodsAttribute()
	{
		return $this->invoice->businessProfile?->paymentInformations->map(function ($paymentInfo) {
			return $paymentInfo->payment_method;
		});
	}

}
