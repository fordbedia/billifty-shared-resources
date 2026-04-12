<?php

namespace BilliftySDK\SharedResources\Modules\User\Models;


use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\BusinessProfiles;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Clients;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Workspace;
use BilliftySDK\SharedResources\Modules\Invoicing\Support\ImageUrlTrait;
use BilliftySDK\SharedResources\Modules\Billing\Support\PlanPermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
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
}
