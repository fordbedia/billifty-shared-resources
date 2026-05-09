<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Models;


use Illuminate\Database\Eloquent\Model;
use Stripe\Invoice;

class PaymentLink extends Model
{
    protected $table = 'payment_link';
    protected $guarded = [];

	public function invoice()
	{
		return $this->belongsTo(Invoice::class, 'invoice_id');
	}
}
