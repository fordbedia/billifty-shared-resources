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
                'code'        => 'free',
                'name'        => 'Free',
                'description' => 'Try Billifty with limited clients and invoices.',
                'price_monthly' => 0,
                'price_yearly'  => null,
                'is_default'  => true,
                'sort_order'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'code'        => 'pro',
                'name'        => 'Pro',
                'description' => 'For freelancers and small teams.',
                'price_monthly' => 4.99,
                'price_yearly'  => 49.99,
                'is_default'  => false,
                'sort_order'  => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'code'        => 'premium',
                'name'        => 'Premium',
                'description' => 'Unlimited invoicing and automation.',
                'price_monthly' => 9.99,
                'price_yearly'  => 99.99,
                'is_default'  => false,
                'sort_order'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
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
