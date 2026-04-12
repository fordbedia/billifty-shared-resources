<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Enums;

use BilliftySDK\SharedResources\Modules\User\Models\Plan;

enum PlanCode: string
{
    case FREE = 'free';
    case PRO = 'pro';
    case PREMIUM = 'premium';

	public function planId(): ?int
	{
		return $this->planModel()?->id;
	}

    protected function planModel(): ?Plan
    {
        static $cache = [];

        $code = $this->value;

        if (! array_key_exists($code, $cache)) {
            $cache[$code] = Plan::with('capabilities')
                ->where('code', $code)
                ->first();
        }

        return $cache[$code];
    }

    public function label(): string
    {
        return $this->planModel()?->name ?? ucfirst($this->value);
    }

    public function priceMonthly(): float
    {
        return (float) ($this->planModel()?->price_monthly ?? 0.00);
    }

    public function priceYearly(): float
    {
        return (float) ($this->planModel()?->price_yearly ?? 0.00);
    }

    public function limits(): array
    {
        $plan = $this->planModel();

        if (! $plan) return [
            'business_profiles'  => null,
            'clients'            => null,
            'invoices_per_month' => null,
        ];

        return [
            'business_profiles'  => $plan->capabilityInt('max_business_profiles', null),
            'clients'            => $plan->capabilityInt('max_clients', null),
            'invoices_per_month' => $plan->capabilityInt('max_invoices_per_month', null),
        ];
    }

    public function features(): array
    {
        $plan = $this->planModel();
        $caps = $plan?->capabilitiesArray() ?? [];

        // IMPORTANT:
        // If a capability is inactive, it won't exist in $caps at all (global scope),
        // so we should NOT default it to "advanced" / "basic" — use null if missing.
        return [
            'pdf_export'      => true,

            'pdf_watermark'   => (bool) ($caps['pdf_watermark']   ?? true),
            'email_watermark' => (bool) ($caps['email_watermark'] ?? true),

            'custom_prefix'   => (bool) ($caps['custom_prefix']   ?? false),
            'custom_branding' => (bool) ($caps['custom_branding'] ?? false),

            // templates
            'templates'       => (string) ($caps['templates_tier'] ?? 'basic'),

            'logo_upload'     => (bool) ($caps['logo_upload'] ?? false),

            // reminders
            'automated_reminders' => $caps['automated_reminders'] ?? 'none',

            // payments/currency
            'online_payments' => (bool) ($caps['online_payments'] ?? false),
            'multi_currency'  => (bool) ($caps['multi_currency']  ?? false),
            'ai_invoice_assistant' => (bool) ($caps['ai_invoice_assistant'] ?? false),

            // branding/support
            'email_branding'  => $caps['email_branding'] ?? 'billifty_footer',
            'support'         => $caps['support_level'] ?? 'email',

            // analytics: if row is inactive it should be absent => return null
            'analytics'       => $caps['analytics_tier'] ?? null,

            // raw for debugging
            'raw'             => $caps,
        ];
    }

    public function actions(): array
    {
        $plan = $this->planModel();

        if (! $plan || ! $plan->relationLoaded('capabilities')) {
            return [
                'text1'      => null,
                'btn'        => null,
                'upper_text' => null,
                'card_label' => null,
            ];
        }

        $marketing = $plan->capabilities
            ->where('group', 'marketing')
            ->mapWithKeys(fn ($cap) => [$cap->key => $cap->cast_value]);

        return [
            'text1'      => $marketing['cta_text1'] ?? null,
            'btn'        => $marketing['cta_btn'] ?? null,
            'upper_text' => $marketing['cta_upper_text'] ?? null,
            'card_label' => $marketing['cta_card_label'] ?? null,
        ];
    }

    /**
     * Pricing bullets — from capabilities.description (features group only)
     * Only active rows appear due to global scope.
     */
    public function featuresText(): array
	{
		$plan = $this->planModel();

		if (! $plan || ! $plan->relationLoaded('capabilities')) {
			return [];
		}

		// Add a single "included from previous plan" line (once)
		$additional = match ($plan->code) {
			self::PRO->value     => ['All Free features'],
			self::PREMIUM->value => ['All Pro features'],
			default              => [],
		};

		$bullets = $plan->capabilities
			->where('group', 'features')
			->filter(fn ($cap) => (string) ($cap->description ?? '') !== '')
			->sortBy(fn ($cap) => (int) (($cap->meta['sort'] ?? 9999)))
			->map(fn ($cap) => (string) $cap->description)
			->values()
			->all();

		// Merge + ensure unique (optional, but nice)
		return array_values(array_unique(array_merge($additional, $bullets)));
	}

    public function billingCycles(): array
    {
        $monthly = $this->priceMonthly();
        $yearly  = $this->priceYearly();

        return [
            'monthly' => [
                'price'    => $monthly,
                'interval' => 'monthly',
            ],
            'yearly' => [
                'price'             => $yearly,
                'interval'          => 'yearly',
                'discount_applied'  => $yearly > 0,
                'discount_percent'  => 20,
            ],
        ];
    }

    public function toArray(): array
    {
        return [
            'code'          => $this->value,
            'actions'       => $this->actions(),
            'name'          => $this->label(),
            'monthly'       => $this->priceMonthly(),
            'yearly'        => $this->priceYearly(),
            'limits'        => $this->limits(),
            'features'      => $this->features(),
            'billing'       => $this->billingCycles(),
            'features_text' => $this->featuresText(),
        ];
    }

    public static function all(): array
    {
        return array_map(
            fn (self $p) => $p->toArray(),
            self::cases(),
        );
    }
}
