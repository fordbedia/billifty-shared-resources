<?php

namespace BilliftySDK\SharedResources\Modules\User\Models;


use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\BusinessProfiles;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
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
    use HasFactory, Notifiable, HasApiTokens;

	protected $appends = [
        'plan_capabilities'
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

    // Example relationships
    public function businessProfiles()
    {
        return $this->hasMany(BusinessProfiles::class);
    }

	public function subscription()
	{
		return $this->hasOne(UserSubscription::class, 'user_id', 'id');
	}

	public function getPlanCapabilitiesAttribute(): array
    {
        $plan = $this->plan;

        if (! $plan) {
            return [];
        }

        // Fetch capabilities: ['key' => PlanCapability]
        $capabilities = $plan->capabilities()
            ->get()
            ->keyBy('key');

        // Helpers to read typed values from capabilities:
        $getInt = function (string $key, ?int $default = null) use ($capabilities) {
            /** @var PlanCapability|null $cap */
            $cap = $capabilities[$key] ?? null;
            if (! $cap) return $default;
            $val = $cap->cast_value;
            // convention: 0 + meta['unlimited'] = unlimited
            if (is_int($val) && $val === 0 && ($cap->meta['unlimited'] ?? false)) {
                return null; // treat as unlimited
            }
            return is_int($val) ? $val : $default;
        };

        $getBool = function (string $key, bool $default = false) use ($capabilities) {
            /** @var PlanCapability|null $cap */
            $cap = $capabilities[$key] ?? null;
            if (! $cap) return $default;
            return (bool) $cap->cast_value;
        };

        $getString = function (string $key, ?string $default = null) use ($capabilities) {
            /** @var PlanCapability|null $cap */
            $cap = $capabilities[$key] ?? null;
            if (! $cap) return $default;
            return (string) $cap->cast_value;
        };

        // Limits
        $maxBusinessProfiles   = $getInt('max_business_profiles', null);
        $maxClients            = $getInt('max_clients', null);
        $maxInvoicesPerMonth   = $getInt('max_invoices_per_month', null);

        // Flags
        $pdfWatermark          = $getBool('pdf_watermark', true);
        $emailWatermark        = $getBool('email_watermark', true);
        $onlinePayments        = $getBool('online_payments', false);
        $automatedReminders    = $getBool('automated_reminders', false);

        // Optional: support level, logo upload, etc.
        $supportLevel          = $getString('support_level', 'none');
        $logoUpload            = $getBool('logo_upload', false);

        // Get current usage for allowed checks
        $currentBusinessProfiles = $this->businessProfiles()->count();
        $currentInvoicesThisMonth = $this->invoices()
            ->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])->count();

        $canCreateBusinessProfile =
            is_null($maxBusinessProfiles) || $currentBusinessProfiles < $maxBusinessProfiles;

        $canCreateInvoice =
            is_null($maxInvoicesPerMonth) || $currentInvoicesThisMonth < $maxInvoicesPerMonth;

        $allowed = [
            'create_business_profile' => $canCreateBusinessProfile,
            'create_invoice'          => $canCreateInvoice,
            'online_payments'         => $onlinePayments,
            'automated_reminders'     => $automatedReminders,
            'logo_upload'             => $logoUpload,
        ];

        $notAllowed = [];
        foreach ($allowed as $key => $val) {
            $notAllowed[$key] = ! $val;
        }

        return [
            'plan' => [
                'id'   => $plan->id,
                'code' => $plan->code,
                'name' => $plan->name,
            ],
            'limits' => [
                'max_business_profiles'      => $maxBusinessProfiles,
                'max_clients'                => $maxClients,
                'max_invoices_per_month'     => $maxInvoicesPerMonth,
                'current_business_profiles'  => $currentBusinessProfiles,
                'current_invoices_this_month'=> $currentInvoicesThisMonth,
            ],
            'flags' => [
                'pdf_watermark'     => $pdfWatermark,
                'email_watermark'   => $emailWatermark,
                'support_level'     => $supportLevel,
            ],
            'allowed'     => $allowed,
            'not_allowed' => $notAllowed,
        ];
    }
}
