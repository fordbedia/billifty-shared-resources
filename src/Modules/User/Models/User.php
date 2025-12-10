<?php

namespace BilliftySDK\SharedResources\Modules\User\Models;


use BilliftySDK\SharedResources\Modules\Invoicing\Models\BusinessProfiles;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

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

	protected static function booted()
	{
		static::saving(function ($user) {
			if (empty($user->name)) {
				if ($user->fname || $user->lname) {
					$user->name = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));
				}
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

	public function getPlanCapabilitiesAttribute(): array
    {
        $plan = $this->plan;

        if (! $plan) {
            return [];
        }

        // You can fetch counts from relationships or pass them from a service.
        $context = [
            'business_profiles_count'    => $this->businessProfiles()->count() ?? 0,
            'invoices_this_month'       => $this->invoices()
                ->whereBetween('created_at', [
                    now()->startOfMonth(),
                    now()->endOfMonth(),
                ])->count() ?? 0,
        ];

        $capabilities = PlanCapabilities::fromPlan($plan);

        return $capabilities->toArrayForUser($this, $context);
    }

    // Example relationships
    public function businessProfiles()
    {
        return $this->hasMany(BusinessProfiles::class);
    }
}
