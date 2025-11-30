<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Invoices extends Model
{
	use SoftDeletes;

    protected $table = 'invoices';
	protected $guarded = [];

	protected $appends = [
        'pdf_url',
    ];

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
			'businessProfile.paymentInformation',
			'client',
			'items',
			'colorScheme.colors',
			'template.category',
			'currency',
		];
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class, 'currency_id');
	}

	public function getPdfUrlAttribute(): ?string
    {
        if (!$this->pdf_path || !$this->pdf_disk) {
            return null;
        }

        return Storage::disk($this->pdf_disk)->url($this->pdf_path);
    }
}
