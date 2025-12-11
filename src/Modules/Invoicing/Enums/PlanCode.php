<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Enums;

use BilliftySDK\SharedResources\Modules\User\Models\Plan;

enum PlanCode: string
{
    case FREE = 'free';
    case PRO = 'pro';
    case PREMIUM = 'premium';

    /**
     * Resolve the backing Plan model (with capabilities), using a per-request static cache.
     */
    protected function planModel(): ?Plan
    {
        // Static local cache — allowed in enums
        static $cache = [];

        $code = $this->value;

        if (! array_key_exists($code, $cache)) {
            $cache[$code] = Plan::with('capabilities')
                ->where('code', $code)
                ->first();
        }

        return $cache[$code];
    }

    /**
     * Human readable name.
     */
    public function label(): string
    {
        if ($plan = $this->planModel()) {
            return $plan->name ?? ucfirst($this->value);
        }

        // Fallback (original)
        return match ($this) {
            self::FREE    => 'Free',
            self::PRO     => 'Pro',
            self::PREMIUM => 'Premium',
        };
    }

    /**
     * Monthly price (USD).
     */
    public function priceMonthly(): float
    {
        if ($plan = $this->planModel()) {
            return (float) $plan->price_monthly;
        }

        // Fallback (original)
        return match ($this) {
            self::FREE    => 0.00,
            self::PRO     => 4.99,
            self::PREMIUM => 9.99,
        };
    }

    /**
     * Yearly price (USD).
     */
    public function priceYearly(): float
    {
        if ($plan = $this->planModel()) {
            return (float) ($plan->price_yearly ?? 0.00);
        }

        // Fallback (original)
        return match ($this) {
            self::FREE    => 0.00,
            self::PRO     => 49.99,
            self::PREMIUM => 99.99,
        };
    }

    /**
     * Usage limits per plan.
     */
    public function limits(): array
    {
        if ($plan = $this->planModel()) {
            // assumes Plan has capabilityInt() helper
            return [
                'business_profiles'  => $plan->capabilityInt('max_business_profiles', null),
                'clients'            => $plan->capabilityInt('max_clients', null),
                'invoices_per_month' => $plan->capabilityInt('max_invoices_per_month', null),
            ];
        }

        // Fallback (original hardcoded structure)
        return match ($this) {
            self::FREE => [
                'business_profiles'  => 1,
                'clients'            => 5,
                'invoices_per_month' => 5,
            ],
            self::PRO => [
                'business_profiles'  => 3,
                'clients'            => null,
                'invoices_per_month' => 10,
            ],
            self::PREMIUM => [
                'business_profiles'  => null,
                'clients'            => null,
                'invoices_per_month' => null,
            ],
        };
    }

    /**
     * Feature flags for each plan.
     * DB-driven if possible, otherwise falls back to original enum data.
     */
    public function features(): array
    {
        if ($plan = $this->planModel()) {
            // assumes Plan::capabilitiesArray() → [key => typed_value]
            $caps = $plan->capabilitiesArray();

            return [
                // Core
                'pdf_export'      => true,

                // Flags derived from capabilities
                'pdf_watermark'   => (bool) ($caps['pdf_watermark']   ?? true),
                'email_watermark' => (bool) ($caps['email_watermark'] ?? true),
                'custom_prefix'   => (bool) ($caps['custom_prefix']   ?? false),
                'custom_branding' => (bool) ($caps['custom_branding'] ?? false),
                'automation'      => (bool) ($caps['automation']      ?? false),
                'analytics'       => (string) ($caps['analytics_tier'] ?? 'basic'),
                'multi_templates' => (bool) ($caps['multi_templates'] ?? false),

                'email_branding'      => (string) ($caps['email_branding']   ?? 'billifty_footer'),
                'templates'           => (string) ($caps['templates_tier']   ?? 'basic'),
                'logo_upload'         => (bool) ($caps['logo_upload']        ?? false),
                'automated_reminders' => (string) ($caps['automated_reminders'] ?? 'none'),
                'online_payments'     => (bool) ($caps['online_payments']    ?? false),
                'multi_currency'      => (bool) ($caps['multi_currency']     ?? false),
                'support'             => (string) ($caps['support_level']    ?? 'email'),

                // Expose raw capabilities for future / debugging
                'raw'                 => $caps,
            ];
        }

        // Fallback (exactly your original enum data)
        return match ($this) {
            self::FREE => [
                'pdf_export'      => true,
                'pdf_watermark'   => true,
                'email_watermark' => true,
                'custom_prefix'   => false,
                'custom_branding' => false,
                'automation'      => false,
                'analytics'       => 'basic',
                'multi_templates' => false,

                'email_branding'        => 'billifty_footer',
                'templates'             => 'basic',
                'logo_upload'           => false,
                'automated_reminders'   => 'none',
                'online_payments'       => false,
                'multi_currency'        => false,
                'support'               => 'email',
            ],
            self::PRO => [
                'pdf_export'      => true,
                'pdf_watermark'   => false,
                'email_watermark' => true,
                'custom_prefix'   => true,
                'custom_branding' => true,
                'automation'      => false,
                'analytics'       => 'standard',
                'multi_templates' => true,

                'email_branding'        => 'small_footer',
                'templates'             => 'multiple',
                'logo_upload'           => true,
                'automated_reminders'   => 'manual',
                'online_payments'       => false,
                'multi_currency'        => false,
                'support'               => 'email',
            ],
            self::PREMIUM => [
                'pdf_export'      => true,
                'pdf_watermark'   => false,
                'email_watermark' => false,
                'custom_prefix'   => true,
                'custom_branding' => true,
                'automation'      => true,
                'analytics'       => 'advanced',
                'multi_templates' => true,

                'email_branding'        => 'none',
                'templates'             => 'all_advanced',
                'logo_upload'           => true,
                'automated_reminders'   => 'automatic',
                'online_payments'       => true,
                'multi_currency'        => true,
                'support'               => 'priority',
            ],
        };
    }

    /**
     * CTA / marketing copy – unchanged from your original.
     */
    public function actions(): array
    {
        return match ($this) {
            self::FREE => [
                'text1'      => 'Perfect for trying out Billifty.',
                'btn'        => 'Get started free',
                'upper_text' => 'Start here',
                'card_label' => null,
            ],
            self::PRO => [
                'text1'      => 'Everything you need to invoice clients professionally.',
                'btn'        => 'Upgrade to Pro',
                'upper_text' => null,
                'card_label' => 'BEST FOR FREELANCERS',
            ],
            self::PREMIUM => [
                'text1'      => 'Unlimited invoicing with advanced automation.',
                'btn'        => 'Go Premium',
                'upper_text' => 'For growing teams',
                'card_label' => null,
            ],
        };
    }

    /**
     * Short bullet list for pricing cards – unchanged.
     */
    public function featuresText(): array
    {
        return match ($this) {
            self::FREE => [
                'Export invoices to PDF',
                'Basic invoice template',
                'Auto-calculated totals',
                'Save as Draft',
                '"Powered by Billifty" watermark',
            ],
            self::PRO => [
                'All Free features',
                'No PDF watermark',
                'Multiple invoice templates',
                'Custom brand colors',
                'Upload business logo',
                'Custom invoice numbering',
                'Payment status tracking',
                'Export to CSV',
                '1 attachment per invoice',
            ],
            self::PREMIUM => [
                'All Pro features',
                'No branding on emails',
                'Automated invoice reminders',
                'Online payment links',
                'Multi-currency support',
                'Advanced analytics dashboard',
                'Estimate to invoice conversion',
                'Unlimited attachments',
                'Priority support',
            ],
        };
    }

    /**
     * Monthly + yearly price structure.
     */
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

    /**
     * For API response – this is what your pricing page is using.
     */
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

    /**
     * Return all plans as an array of pricing-card-friendly data.
     */
    public static function all(): array
    {
        return array_map(
            fn (self $p) => $p->toArray(),
            self::cases(),
        );
    }
}
