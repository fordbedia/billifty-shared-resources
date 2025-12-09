<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Enums;

enum PlanCode: string
{
    case FREE = 'free';
    case PRO = 'pro';
    case PREMIUM = 'premium';

    /**
     * Human readable name
     */
    public function label(): string
    {
        return match($this) {
            self::FREE => 'Free',
            self::PRO => 'Pro',
            self::PREMIUM => 'Premium',
        };
    }

    /**
     * Monthly price (USD)
     */
    public function priceMonthly(): float
    {
        return match($this) {
            self::FREE => 0.00,
            self::PRO => 4.99,
            self::PREMIUM => 9.99,
        };
    }

    /**
     * Yearly price (USD) with 20% discount applied.
     * You may round or adjust based on marketing preference.
     */
    public function priceYearly(): float
    {
        return match($this) {
            self::FREE => 0.00,

            // Rounded pricing for better marketing psychology
            self::PRO => 49.99,        // Originally 47.88
            self::PREMIUM => 99.99,    // Originally 95.88
        };
    }

    /**
     * Usage limits per plan
     */
    public function limits(): array
    {
        return match($this) {
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
     * Feature flags for each plan
     *
     * These values are now rich enough to drive:
     * - pricing cards
     * - the "Compare all features" table
     */
    public function features(): array
    {
        return match($this) {
            self::FREE => [
                // existing flags
                'pdf_export'      => true,
                'pdf_watermark'   => true,
                'email_watermark' => true,
                'custom_prefix'   => false,
                'custom_branding' => false,
                'automation'      => false,
                'analytics'       => 'basic',     // basic | standard | advanced
                'attachments'     => 0,
                'multi_templates' => false,

                // NEW: values used by comparison table
                'email_branding'        => 'billifty_footer',  // billifty_footer | small_footer | none
                'templates'             => 'basic',            // basic | multiple | all_advanced
                'logo_upload'           => false,
                'automated_reminders'   => 'none',             // none | manual | automatic
                'online_payments'       => false,
                'multi_currency'        => false,
                'support'               => 'email',            // email | priority
            ],

            self::PRO => [
                'pdf_export'      => true,
                'pdf_watermark'   => false,
                'email_watermark' => true,
                'custom_prefix'   => true,
                'custom_branding' => true,
                'automation'      => false,
                'analytics'       => 'standard',
                'attachments'     => 1,
                'multi_templates' => true,

                // comparison table values
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
                'attachments'     => 'unlimited',
                'multi_templates' => true,

                // comparison table values
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

    public function actions(): array
    {
        return match($this) {
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

    public function featuresText(): array
    {
        return match($this) {
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
     * For API response
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
     * Return monthly + yearly price structure
     */
    public function billingCycles(): array
    {
        return [
            'monthly' => [
                'price'    => $this->priceMonthly(),
                'interval' => 'monthly',
            ],
            'yearly' => [
                'price'             => $this->priceYearly(),
                'interval'          => 'yearly',
                'discount_applied'  => true,
                'discount_percent'  => 20,
            ],
        ];
    }

    /**
     * Return all plans as array
     */
    public static function all(): array
    {
        return array_map(
            fn (self $p) => $p->toArray(),
            self::cases()
        );
    }
}
