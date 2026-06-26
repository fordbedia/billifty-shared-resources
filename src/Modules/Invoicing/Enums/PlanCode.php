<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Enums;

use BilliftySDK\SharedResources\Modules\User\Models\Plan;

enum PlanCode: string
{
    case FREE = 'free';
    case PRO = 'pro';
    case PREMIUM = 'premium';

	public static function fromPlan(Plan $plan): array
	{
		return self::from($plan->code)->toArray($plan);
	}

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

    public function label(?Plan $plan = null): string
    {
        return $this->resolvePlan($plan)?->name ?? ucfirst($this->value);
    }

    public function description(?Plan $plan = null): ?string
    {
        return $this->resolvePlan($plan)?->description;
    }

    public function priceMonthly(?Plan $plan = null): float
    {
        return (float) ($this->resolvePlan($plan)?->price_monthly ?? 0.00);
    }

    public function priceYearly(?Plan $plan = null): ?float
    {
        $yearly = $this->resolvePlan($plan)?->price_yearly;

        return $yearly === null ? null : (float) $yearly;
    }

    public function limits(?Plan $plan = null): array
    {
        $plan = $this->resolvePlan($plan);

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

    public function features(?Plan $plan = null): array
    {
        $plan = $this->resolvePlan($plan);
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

    public function actions(?Plan $plan = null): array
    {
        $plan = $this->resolvePlan($plan);

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
    public function featuresText(?Plan $plan = null): array
	{
		$plan = $this->resolvePlan($plan);

		if (! $plan || ! $plan->relationLoaded('capabilities')) {
			return [];
		}

		// Add a single "included from previous plan" line (once)
		$additional = match ($plan->code) {
			self::PRO->value     => ['All Free features'],
			self::PREMIUM->value => [
				'All Pro features',
				'Unlimited invoices',
				'Unlimited clients',
				'Unlimited Business Profiles'
			],
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

    public function billingCycles(?Plan $plan = null): array
    {
        $monthly = $this->priceMonthly($plan);
        $yearly  = $this->priceYearly($plan);

        return [
            'monthly' => [
                'price'    => $monthly,
                'interval' => 'monthly',
            ],
            'yearly' => [
                'price'             => $yearly ?? 0,
                'interval'          => 'yearly',
                'discount_applied'  => $yearly !== null && $yearly > 0,
                'discount_percent'  => 20,
            ],
        ];
    }

    public function toArray(?Plan $plan = null): array
    {
        return [
            'code'          => $this->value,
            'actions'       => $this->actions($plan),
            'name'          => $this->label($plan),
            'description'   => $this->description($plan),
            'monthly'       => $this->priceMonthly($plan),
            'yearly'        => $this->priceYearly($plan),
            'limits'        => $this->limits($plan),
            'features'      => $this->features($plan),
            'billing'       => $this->billingCycles($plan),
            'features_text' => $this->featuresText($plan),
        ];
    }

	protected function resolvePlan(?Plan $plan = null): ?Plan
	{
		if ($plan && ! $plan->relationLoaded('capabilities')) {
			$plan->load('capabilities');
		}

		return $plan ?? $this->planModel();
	}

    public static function all(): array
    {
        return array_map(
            fn (self $p) => $p->toArray(),
            self::cases(),
        );
    }
}
