<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Models;


use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PaymentLink extends Model
{
    protected $table = 'payment_link';
    protected $guarded = [];
	public $appends = ['payment_methods', 'user'];

	public function invoice()
	{
		return $this->belongsTo(Invoices::class, 'invoice_id');
	}

	public static function relationships()
	{
		return [
			'invoice.items',
			'invoice.client',
			'invoice.businessProfile.paymentInformations',
			'invoice.currency',
			'invoice.workspace.businessProfile'
		];
	}

	public function getPaymentMethodsAttribute()
	{
		return $this->invoice->businessProfile?->paymentInformations->map(function ($paymentInfo) {
			return $paymentInfo->payment_method;
		});
	}

	public function user(): Attribute
	{
		return Attribute::make(get: fn () => $this->invoice->workspace->user);
	}

}
