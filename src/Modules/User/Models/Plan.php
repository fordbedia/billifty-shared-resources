<?php

namespace BilliftySDK\SharedResources\Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
	protected $table = 'plans';
    protected $fillable = [
        'code',
        'name',
        'description',
        'price_monthly',
        'price_yearly',
        'max_business_profiles',
        'max_clients',
        'max_invoices_per_month',
        'pdf_watermark',
        'email_watermark',
        'allows_online_payments',
        'allows_automated_reminders',
        'is_default',
        'sort_order',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
