<?php

namespace BilliftySDK\SharedResources\Modules\User\Models;


use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\BusinessProfiles;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Clients;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Workspace;
use BilliftySDK\SharedResources\Modules\Invoicing\Support\ImageUrlTrait;
use BilliftySDK\SharedResources\Modules\Billing\Support\PlanPermission;
use BilliftySDK\SharedResources\Modules\User\Notifications\CustomVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, ImageUrlTrait;

	protected $appends = [
        'plan_capabilities',
		'image_url'
    ];

	protected $with = ['subscription'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
		'plan_id',
		'fname',
		'lname',
        'name',
        'email',
        'password',
		'provider',
		'provider_id',
		'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

	public function sendEmailVerificationNotification(): void
	{
		$this->notify(new CustomVerifyEmail);
	}

	protected function imageAttributeName(): string
	{
		return 'avatar';
	}

	protected static function booted()
	{
		static::saving(function ($user) {
			if ($user->fname || $user->lname) {
				$user->name = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));
			}
		});

		static::created(function (self $user) {
			Workspace::ensureDefaultForUser($user);
		});
	}


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

	public function workspaces()
	{
		return $this->hasMany(Workspace::class, 'user_id', 'id');
	}

	public function defaultWorkspace()
	{
		return $this->hasOne(Workspace::class, 'user_id', 'id')
			->where('is_default', 1);
	}

	public function resolveDefaultWorkspace(): Workspace
	{
		return Workspace::ensureDefaultForUser($this);
	}

	public function invoices()
	{
		return $this->hasManyThrough(
			Invoices::class,
			Workspace::class,
			'user_id',
			'workspace_id',
			'id',
			'id'
		);
	}

	public function plan()
	{
		return $this->belongsTo(Plan::class);
	}

    // Example relationships
    public function businessProfiles()
    {
        return $this->hasMany(BusinessProfiles::class);
    }

	public function clients()
	{
		return $this->hasMany(Clients::class, 'user_id', 'id');
	}

	public function subscription()
	{
		return $this->hasOne(UserSubscription::class, 'user_id', 'id');
	}

	public function getPlanCapabilitiesAttribute(): array
	{
		return PlanPermission::attempt($this)->toArray();
	}

	public function overAllUsages(): Attribute
	{
		return Attribute::make(get: fn () => [
			'invoices' => $this->invoices()->count(),
			'business_profiles' => $this->businessProfiles()->count(),
			'clients' => $this->clients()->count(),
		]);
	}
}
