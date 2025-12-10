<?php

namespace BilliftySDK\SharedResources\Modules\User\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use BilliftySDK\SharedResources\SDK\Database\MakeSeeder;
use Illuminate\Support\Facades\DB;

class PlansSeeder extends MakeSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plans')->insert([
            [
                'code'                     => 'free',
                'name'                     => 'Free',
                'description'              => 'Perfect for trying Billifty with limited clients and invoices.',
                'price_monthly'            => 0,
                'price_yearly'             => null,
                'max_business_profiles'    => 1,
                'max_clients'              => 5,
                'max_invoices_per_month'   => 5,
                'pdf_watermark'            => true,
                'email_watermark'          => true,
                'allows_online_payments'   => false,
                'allows_automated_reminders' => false,
                'is_default'               => true,
                'sort_order'               => 1,
                'created_at'               => now(),
                'updated_at'               => now(),
            ],
            [
                'code'                     => 'pro',
                'name'                     => 'Pro',
                'description'              => 'For freelancers and small businesses starting to grow.',
                'price_monthly'            => 4.99, // or 2.99 if you want starter pricing
                'price_yearly'             => 49.99, // optional
                'max_business_profiles'    => 3,
                'max_clients'              => null, // unlimited
                'max_invoices_per_month'   => 10,
                'pdf_watermark'            => false,
                'email_watermark'          => true,
                'allows_online_payments'   => false,
                'allows_automated_reminders' => false,
                'is_default'               => false,
                'sort_order'               => 2,
                'created_at'               => now(),
                'updated_at'               => now(),
            ],
            [
                'code'                     => 'premium',
                'name'                     => 'Premium',
                'description'              => 'For businesses that need unlimited invoicing and automation.',
                'price_monthly'            => 9.99, // or 8.99 if you want starter pricing
                'price_yearly'             => 99.99,
                'max_business_profiles'    => null, // unlimited
                'max_clients'              => null, // unlimited
                'max_invoices_per_month'   => null, // unlimited
                'pdf_watermark'            => false,
                'email_watermark'          => false,
                'allows_online_payments'   => true,
                'allows_automated_reminders' => true,
                'is_default'               => false,
                'sort_order'               => 3,
                'created_at'               => now(),
                'updated_at'               => now(),
            ],
        ]);
    }

    /**
     * Revert the database seeds.
     */
    public function revert(): void
    {
        DB::table('plans')->truncate();
    }
}
