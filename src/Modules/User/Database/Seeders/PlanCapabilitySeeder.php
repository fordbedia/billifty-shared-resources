<?php

namespace BilliftySDK\SharedResources\Modules\User\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use BilliftySDK\SharedResources\SDK\Database\MakeSeeder;
use Illuminate\Support\Facades\DB;

class PlanCapabilitySeeder extends MakeSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ----------------------------------------------------------------------------
		// Seed the plan_capabilities
		// ----------------------------------------------------------------------------
		// get plan IDs
        $plans = DB::table('plans')
            ->whereIn('code', ['free', 'pro', 'premium'])
            ->pluck('id', 'code'); // ['free' => 1, 'pro' => 2, ...]

        $rows = [];

        // ---------------- Free ----------------

        // Limits
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'max_business_profiles',
            'label'      => 'Max Business Profiles',
            'type'       => 'int',
            'value'      => '1',
            'meta'       => null,
			'model_relationship' => 'businessProfiles',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'max_clients',
            'label'      => 'Max Clients',
            'type'       => 'int',
            'value'      => '5',
            'meta'       => null,
			'model_relationship' => 'clients',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'max_invoices_per_month',
            'label'      => 'Max Invoices / Month',
            'type'       => 'int',
            'value'      => '5',
            'meta'       => null,
			'model_relationship' => 'invoices',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Core feature flags
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'pdf_watermark',
            'label'      => 'PDF Watermark',
            'type'       => 'bool',
            'value'      => 'true',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'email_watermark',
            'label'      => 'Email Watermark',
            'type'       => 'bool',
            'value'      => 'true',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Pricing features (from your enum)
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'custom_prefix',
            'label'      => 'Custom Invoice Number Prefix',
            'type'       => 'bool',
            'value'      => 'false',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'custom_branding',
            'label'      => 'Custom Branding',
            'type'       => 'bool',
            'value'      => 'false',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'automation',
            'label'      => 'Automation Features',
            'type'       => 'bool',
            'value'      => 'false',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'analytics_tier',
            'label'      => 'Analytics Tier',
            'type'       => 'string',
            'value'      => 'basic',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'multi_templates',
            'label'      => 'Multiple Templates',
            'type'       => 'bool',
            'value'      => 'false',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Comparison table extras
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'email_branding',
            'label'      => 'Email Branding',
            'type'       => 'string',
            'value'      => 'billifty_footer', // billifty_footer | small_footer | none
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'templates_tier',
            'label'      => 'Templates Tier',
            'type'       => 'string',
            'value'      => 'basic', // basic | multiple | all_advanced
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'logo_upload',
            'label'      => 'Logo Upload',
            'type'       => 'bool',
            'value'      => 'false',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'automated_reminders',
            'label'      => 'Automated Reminders',
            'type'       => 'string',
            'value'      => 'none', // none | manual | automatic
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'online_payments',
            'label'      => 'Online Payments',
            'type'       => 'bool',
            'value'      => 'false',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'multi_currency',
            'label'      => 'Multi-currency Support',
            'type'       => 'bool',
            'value'      => 'false',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['free'],
            'key'        => 'support_level',
            'label'      => 'Support Level',
            'type'       => 'string',
            'value'      => 'email',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ---------------- Pro ----------------

        // Limits
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'max_business_profiles',
            'label'      => 'Max Business Profiles',
            'type'       => 'int',
            'value'      => '3',
            'meta'       => null,
			'model_relationship' => 'businessProfiles',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'max_clients',
            'label'      => 'Max Clients',
            'type'       => 'int',
            'value'      => '0', // 0 + meta[unlimited] = unlimited
            'meta'       => json_encode(['unlimited' => true]),
			'model_relationship' => 'clients',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'max_invoices_per_month',
            'label'      => 'Max Invoices / Month',
            'type'       => 'int',
            'value'      => '10',
            'meta'       => null,
			'model_relationship' => 'invoices',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Core flags
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'pdf_watermark',
            'label'      => 'PDF Watermark',
            'type'       => 'bool',
            'value'      => 'false',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'email_watermark',
            'label'      => 'Email Watermark',
            'type'       => 'bool',
            'value'      => 'true',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'custom_prefix',
            'label'      => 'Custom Invoice Number Prefix',
            'type'       => 'bool',
            'value'      => 'true',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'custom_branding',
            'label'      => 'Custom Branding',
            'type'       => 'bool',
            'value'      => 'true',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'automation',
            'label'      => 'Automation Features',
            'type'       => 'bool',
            'value'      => 'false',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'analytics_tier',
            'label'      => 'Analytics Tier',
            'type'       => 'string',
            'value'      => 'standard',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'multi_templates',
            'label'      => 'Multiple Templates',
            'type'       => 'bool',
            'value'      => 'true',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'email_branding',
            'label'      => 'Email Branding',
            'type'       => 'string',
            'value'      => 'small_footer',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'templates_tier',
            'label'      => 'Templates Tier',
            'type'       => 'string',
            'value'      => 'multiple',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'logo_upload',
            'label'      => 'Logo Upload',
            'type'       => 'bool',
            'value'      => 'true',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'automated_reminders',
            'label'      => 'Automated Reminders',
            'type'       => 'string',
            'value'      => 'manual',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'online_payments',
            'label'      => 'Online Payments',
            'type'       => 'bool',
            'value'      => 'false',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'multi_currency',
            'label'      => 'Multi-currency Support',
            'type'       => 'bool',
            'value'      => 'false',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['pro'],
            'key'        => 'support_level',
            'label'      => 'Support Level',
            'type'       => 'string',
            'value'      => 'email',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ---------------- Premium ----------------

        // Limits
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'max_business_profiles',
            'label'      => 'Max Business Profiles',
            'type'       => 'int',
            'value'      => '0', // unlimited
            'meta'       => json_encode(['unlimited' => true]),
			'model_relationship' => 'businessProfiles',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'max_clients',
            'label'      => 'Max Clients',
            'type'       => 'int',
            'value'      => '0', // unlimited
            'meta'       => json_encode(['unlimited' => true]),
			'model_relationship' => 'clients',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'max_invoices_per_month',
            'label'      => 'Max Invoices / Month',
            'type'       => 'int',
            'value'      => '0', // unlimited
            'meta'       => json_encode(['unlimited' => true]),
			'model_relationship' => 'invoices',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Core flags
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'pdf_watermark',
            'label'      => 'PDF Watermark',
            'type'       => 'bool',
            'value'      => 'false',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'email_watermark',
            'label'      => 'Email Watermark',
            'type'       => 'bool',
            'value'      => 'false',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'custom_prefix',
            'label'      => 'Custom Invoice Number Prefix',
            'type'       => 'bool',
            'value'      => 'true',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'custom_branding',
            'label'      => 'Custom Branding',
            'type'       => 'bool',
            'value'      => 'true',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'automation',
            'label'      => 'Automation Features',
            'type'       => 'bool',
            'value'      => 'true',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'analytics_tier',
            'label'      => 'Analytics Tier',
            'type'       => 'string',
            'value'      => 'advanced',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'multi_templates',
            'label'      => 'Multiple Templates',
            'type'       => 'bool',
            'value'      => 'true',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'email_branding',
            'label'      => 'Email Branding',
            'type'       => 'string',
            'value'      => 'none',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'templates_tier',
            'label'      => 'Templates Tier',
            'type'       => 'string',
            'value'      => 'all_advanced',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'logo_upload',
            'label'      => 'Logo Upload',
            'type'       => 'bool',
            'value'      => 'true',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'automated_reminders',
            'label'      => 'Automated Reminders',
            'type'       => 'string',
            'value'      => 'automatic',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'online_payments',
            'label'      => 'Online Payments',
            'type'       => 'bool',
            'value'      => 'true',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'multi_currency',
            'label'      => 'Multi-currency Support',
            'type'       => 'bool',
            'value'      => 'true',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rows[] = [
            'plan_id'    => $plans['premium'],
            'key'        => 'support_level',
            'label'      => 'Support Level',
            'type'       => 'string',
            'value'      => 'priority',
            'meta'       => null,
			'model_relationship' => 'plan.capabilities',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('plan_capabilities')->insert($rows);
    }

    /**
     * Revert the database seeds.
     */
    public function revert(): void
    {
        //
    }
}
