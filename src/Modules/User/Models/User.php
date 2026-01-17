<?php

namespace BilliftySDK\SharedResources\Modules\User\Models;


use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\BusinessProfiles;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Clients;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Support\ImageUrlTrait;
use BilliftySDK\SharedResources\Modules\User\Support\PlanCapabilities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

	public function invoices(): HasMany
	{
		return $this->hasMany(Invoices::class, 'user_id', 'id');
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
		$plan = $this->plan;
		if (! $plan) return [];

		if (! $plan->relationLoaded('capabilities')) {
			$plan->load('capabilities'); // ActiveScope applies (is_active=1)
		}

		$capsByGroup = $plan->capabilities->groupBy('group');

		$limitsCaps   = $capsByGroup->get('limits', collect());
		$featuresCaps = $capsByGroup->get('features', collect());

		// Build limits dynamically: key => value
		$limits = $limitsCaps->mapWithKeys(function ($cap) use ($plan) {
			$val = match ($cap->type) {
				'int'    => $plan->capabilityInt($cap->key, null),
				'bool'   => $plan->capabilityBool($cap->key, false),
				'string' => $plan->capabilityString($cap->key, null),
				'json'   => $plan->capabilityValue($cap->key, null),
				default  => $plan->capabilityValue($cap->key, null),
			};

			return [$cap->key => $val];
		})->toArray();

		// Build flags dynamically: key => value
		$flags = $featuresCaps->mapWithKeys(function ($cap) use ($plan) {
			$val = match ($cap->type) {
				'int'    => $plan->capabilityInt($cap->key, null),
				'bool'   => $plan->capabilityBool($cap->key, false),
				'string' => $plan->capabilityString($cap->key, null),
				'json'   => $plan->capabilityValue($cap->key, null),
				default  => $plan->capabilityValue($cap->key, null),
			};

			return [$cap->key => $val];
		})->toArray();

		// Allowed: compute automatically for anything that has model_relationship (limits)
		$allowed = [];
		foreach ($limitsCaps as $cap) {
			if (! $cap->model_relationship) continue;
			if (! method_exists($this, $cap->model_relationship)) continue;

			$max = $limits[$cap->key] ?? null;

			// usage mode from meta
			$usageMode = $cap->meta['usage'] ?? null;

			if ($usageMode === 'monthly') {
				$current = $this->{$cap->model_relationship}()
					->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
					->count();
			} else {
				$current = $this->{$cap->model_relationship}()->count();
			}

			$allowed['create:' . $cap->model_relationship] = is_null($max) ? true : ($current < $max);

			// expose current usage dynamically too
			$limits['current:' . $cap->model_relationship] = $current;
		}

		// Allowed for features: bool => itself, string => not none/empty, int => >0/unlimited
		foreach ($featuresCaps as $cap) {
			$val = $flags[$cap->key] ?? null;

			$allowed[$cap->key] = match ($cap->type) {
				'bool'   => (bool) $val,
				'string' => ! empty($val) && strtolower((string) $val) !== 'none',
				'int'    => is_null($val) ? true : ((int) $val > 0),
				default  => (bool) $val,
			};
		}

		$notAllowed = [];
		foreach ($allowed as $k => $v) $notAllowed[$k] = ! $v;

		return [
			'plan' => [
				'id'   => $plan->id,
				'code' => $plan->code,
				'name' => $plan->name,
			],
			'limits'      => $limits,   // dynamic keys
			'flags'       => $flags,    // dynamic keys
			'allowed'     => $allowed,  // dynamic keys
			'not_allowed' => $notAllowed,
		];
	}
}
