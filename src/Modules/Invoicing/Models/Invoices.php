<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;


use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentLink;
use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentRecord;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Invoices extends Model
{
	use SoftDeletes;

	protected $table = 'invoices';
	protected $guarded = [];

	protected $hidden = [
		'workspace',
	];

	protected $appends = [
		'pdf_url',
		'user_id',
		'is_issued',
		'is_draft',
		'is_paid'
	];

	protected $casts = [
		'workspace_id' => 'integer',
	];

	public function businessProfile()
	{
		return $this->belongsTo(BusinessProfiles::class, 'business_profile_id');
	}

	public function workspace()
	{
		return $this->belongsTo(Workspace::class, 'workspace_id');
	}

	public function client()
	{
		return $this->belongsTo(Clients::class, 'client_id');
	}

	public function items()
	{
		return $this->hasMany(InvoiceItems::class, 'invoice_id', 'id')
			->orderBy('position')
			->orderBy('id');
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
			'workspace.user',
			'businessProfile.paymentInformations',
			'paymentLink',
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

	public function getUserIdAttribute(): ?int
	{
		return $this->workspace?->user_id;
	}

	public function getUserAttribute(): ?User
	{
		return $this->workspace?->user;
	}

	public function getPdfUrlAttribute(): ?string
	{
		if (!$this->pdf_path || !$this->pdf_disk) {
			return null;
		}

		return Storage::disk($this->pdf_disk)->url($this->pdf_path);
	}

	public function paymentLink()
	{
		return $this->hasOne(PaymentLink::class, 'invoice_id', 'id');
	}

	public function paymentRecord()
	{
		return $this->hasOne(PaymentRecord::class, 'invoice_id', 'id');
	}

	public function getIsIssuedAttribute()
	{
		return $this->status === 'issued';
	}

	public function getIsDraftAttribute()
	{
		return $this->status === 'draft';
	}

	public function getIsPaidAttribute()
	{
		return $this->status === 'paid';
	}
}
