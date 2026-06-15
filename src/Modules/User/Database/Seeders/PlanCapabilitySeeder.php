<?php

namespace BilliftySDK\SharedResources\Modules\User\Database\Seeders;

use BilliftySDK\SharedResources\SDK\Database\MakeSeeder;
use Illuminate\Support\Facades\DB;

class PlanCapabilitySeeder extends MakeSeeder
{
    public function run(): void
    {
        $now = now();

        $plans = DB::table('plans')
            ->whereIn('code', ['free', 'pro', 'premium'])
            ->pluck('id', 'code');

        $rows = [];

        $add = function (
            string $planCode,
            string $group,
            string $key,
            ?string $label,
            ?string $description,
            string $type,
            ?string $value,
            array|null $meta = null,
            ?string $relationship = null,
            bool $isActive = true
        ) use (&$rows, $plans, $now) {
            $rows[] = [
                'plan_id'            => $plans[$planCode],
                'group'              => $group,
                'key'                => $key,
                'label'              => $label,
                'description'        => $description,
                'type'               => $type,
                'value'              => $value,
                'meta'               => is_null($meta) ? null : json_encode($meta),
                'model_relationship' => $relationship,
                'is_active'          => $isActive,
                'created_at'         => $now,
                'updated_at'         => $now,
            ];
        };

        // ------------------------------------------------------------
        // FREE
        // ------------------------------------------------------------
        $add('free', 'limits', 'max_business_profiles', 'Business Profiles', null, 'int', '1', null, 'businessProfiles', true);
        $add('free', 'limits', 'max_clients', 'Clients', null, 'int', '5', null, 'clients', true);
        $add('free', 'limits', 'max_invoices_per_month', 'Invoices per month', null, 'int', '5', ['usage' => 'monthly'], 'invoices', true);

        $add('free', 'features', 'pdf_watermark', 'PDF Watermark', '“Powered by Billifty” watermark', 'bool', 'true', null, null, true);
        $add('free', 'features', 'email_watermark', 'Email Watermark', 'Billifty branding in emails', 'bool', 'true', null, null, true);

        $add('free', 'features', 'custom_prefix', 'Custom Invoice Numbering', 'Basic invoice numbering', 'bool', 'false', null, null, true);
        $add('free', 'features', 'custom_branding', 'Custom Brand Colors', 'Basic invoice template', 'bool', 'false', null, null, true);
        $add('free', 'features', 'multi_templates', 'Templates', 'Basic invoice template', 'bool', 'false', null, null, true);
        $add('free', 'features', 'logo_upload', 'Logo Upload', null, 'bool', 'false', null, null, true);

        $add('free', 'features', 'automated_reminders', 'Automated Reminders', null, 'string', 'none', null, null, true);
        $add('free', 'features', 'online_payments', 'Online Payments', null, 'bool', 'false', null, null, true);
        $add('free', 'features', 'multi_currency', 'Multi-Currency', null, 'bool', 'false', null, null, true);
        $add('free', 'features', 'ai_invoice_assistant', 'AI Invoice Assistant', null, 'bool', 'false', null, null, true);

        // analytics: keep row, but if you set inactive it disappears everywhere
        $add('free', 'features', 'analytics_tier', 'Analytics', null, 'string', 'basic', null, null, false);

        $add('free', 'features', 'email_branding', 'Email Branding', null, 'string', 'billifty_footer', null, null, true);
        $add('free', 'features', 'templates_tier', 'Templates', null, 'string', 'basic', null, null, true);
        $add('free', 'features', 'support_level', 'Support', null, 'string', 'email', null, null, true);
		$add('free', 'features', 'multi_currency', 'Multi-Currency', 'Multi-currency support', 'bool', 'true', null, null, true);

        // Marketing (CTA)
        $add('free', 'marketing', 'cta_text1', null, null, 'string', 'Perfect for trying out Billifty.', null, null, true);
        $add('free', 'marketing', 'cta_btn', null, null, 'string', 'Get started free', null, null, true);
        $add('free', 'marketing', 'cta_upper_text', null, null, 'string', 'Start here', null, null, true);
        $add('free', 'marketing', 'cta_card_label', null, null, 'string', null, null, null, true);

        // ------------------------------------------------------------
        // PRO
        // ------------------------------------------------------------
        $add('pro', 'limits', 'max_business_profiles', 'Business Profiles', null, 'int', '3', null, 'businessProfiles', true);
        $add('pro', 'limits', 'max_clients', 'Clients', null, 'int', '0', ['unlimited' => true], 'clients', true);
        $add('pro', 'limits', 'max_invoices_per_month', 'Invoices per month', null, 'int', '10', ['usage' => 'monthly'], 'invoices', true);

        $add('pro', 'features', 'pdf_watermark', 'PDF Watermark', 'No PDF watermark', 'bool', 'false', null, null, true);
        $add('pro', 'features', 'email_watermark', 'Email Watermark', 'Watermark on emails', 'bool', 'true', null, null, true);

        $add('pro', 'features', 'custom_prefix', 'Custom Invoice Numbering', 'Custom invoice numbering', 'bool', 'true', null, null, true);
        $add('pro', 'features', 'custom_branding', 'Custom Brand Colors', 'Custom brand colors', 'bool', 'true', null, null, true);
        $add('pro', 'features', 'multi_templates', 'Templates', 'Multiple invoice templates', 'bool', 'true', null, null, true);
        $add('pro', 'features', 'logo_upload', 'Logo Upload', 'Upload business logo', 'bool', 'true', null, null, true);

        $add('pro', 'features', 'automated_reminders', 'Automated Reminders', 'Manual reminders', 'string', 'manual', null, null, true);
        $add('pro', 'features', 'online_payments', 'Online Payments', null, 'bool', 'false', null, null, true);
        $add('pro', 'features', 'multi_currency', 'Multi-Currency', null, 'bool', 'false', null, null, true);
        $add('pro', 'features', 'ai_invoice_assistant', 'AI Invoice Assistant', 'AI invoice assistant chat', 'bool', 'true', null, null, true);

        $add('pro', 'features', 'analytics_tier', 'Analytics', null, 'string', 'standard', null, null, false);

        $add('pro', 'features', 'email_branding', 'Email Branding', null, 'string', 'small_footer', null, null, true);
        $add('pro', 'features', 'templates_tier', 'Templates', null, 'string', 'multiple', null, null, true);
        $add('pro', 'features', 'support_level', 'Support', null, 'string', 'email', null, null, true);
		$add('pro', 'features', 'multi_currency', 'Multi-Currency', 'Multi-currency support', 'bool', 'true', null, null, true);

        $add('pro', 'marketing', 'cta_text1', null, null, 'string', 'Everything you need to invoice clients professionally.', null, null, true);
        $add('pro', 'marketing', 'cta_btn', null, null, 'string', 'Upgrade to Pro', null, null, true);
        $add('pro', 'marketing', 'cta_upper_text', null, null, 'string', null, null, null, true);
        $add('pro', 'marketing', 'cta_card_label', null, null, 'string', 'BEST FOR FREELANCERS', null, null, true);

        // ------------------------------------------------------------
        // PREMIUM
        // ------------------------------------------------------------
        $add('premium', 'limits', 'max_business_profiles', 'Business Profiles', null, 'int', '0', ['unlimited' => true], 'businessProfiles', true);
        $add('premium', 'limits', 'max_clients', 'Clients', null, 'int', '0', ['unlimited' => true], 'clients', true);
        $add('premium', 'limits', 'max_invoices_per_month', 'Invoices per month', null, 'int', '0', ['unlimited' => true, 'usage' => 'monthly'], 'invoices', true);

        $add('premium', 'features', 'pdf_watermark', 'PDF Watermark', 'No branding on PDFs', 'bool', 'false', null, null, true);
        $add('premium', 'features', 'email_watermark', 'Email Watermark', 'No branding on emails', 'bool', 'false', null, null, true);

        $add('premium', 'features', 'custom_prefix', 'Custom Invoice Numbering', 'Custom invoice numbering', 'bool', 'true', null, null, true);
        $add('premium', 'features', 'custom_branding', 'Custom Brand Colors', 'Custom brand colors', 'bool', 'true', null, null, true);
        $add('premium', 'features', 'multi_templates', 'Templates', 'All advanced templates', 'bool', 'true', null, null, true);
        $add('premium', 'features', 'logo_upload', 'Logo Upload', 'Upload business logo', 'bool', 'true', null, null, true);

        $add('premium', 'features', 'automated_reminders', 'Automated Reminders', 'Automated invoice reminders', 'string', 'automatic', null, null, true);
        $add('premium', 'features', 'online_payments', 'Online Payments', 'Online payment links', 'bool', 'true', null, null, true);
        $add('premium', 'features', 'multi_currency', 'Multi-Currency', 'Multi-currency support', 'bool', 'true', null, null, true);
        $add('premium', 'features', 'ai_invoice_assistant', 'AI Invoice Assistant', 'AI invoice assistant chat', 'bool', 'true', null, null, true);

        // If you want analytics hidden: set is_active=false (then it won't show in compare + bullets)
        $add('premium', 'features', 'analytics_tier', 'Analytics', 'Advanced analytics dashboard', 'string', 'advanced', null, null, false);

        $add('premium', 'features', 'email_branding', 'Email Branding', null, 'string', 'none', null, null, true);
        $add('premium', 'features', 'templates_tier', 'Templates', null, 'string', 'all_advanced', null, null, true);
        $add('premium', 'features', 'support_level', 'Support', null, 'string', 'priority', null, null, true);

        $add('premium', 'marketing', 'cta_text1', null, null, 'string', 'Unlimited invoicing with advanced automation.', null, null, true);
        $add('premium', 'marketing', 'cta_btn', null, null, 'string', 'Go Premium', null, null, true);
        $add('premium', 'marketing', 'cta_upper_text', null, null, 'string', 'For growing teams', null, null, true);
        $add('premium', 'marketing', 'cta_card_label', null, null, 'string', null, null, null, true);

        DB::table('plan_capabilities')->upsert(
            $rows,
            ['plan_id', 'key'],
            ['group', 'label', 'description', 'type', 'value', 'meta', 'model_relationship', 'is_active', 'updated_at']
        );
    }

    public function revert(): void
    {
        //
    }
}
