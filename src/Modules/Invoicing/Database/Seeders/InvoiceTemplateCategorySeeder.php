<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Database\Seeders;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\ColorScheme;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\ColorSchemeColor;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceTemplates;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use BilliftySDK\SharedResources\SDK\Database\MakeSeeder;
use Illuminate\Support\Facades\DB;

class InvoiceTemplateCategorySeeder extends MakeSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

				// Create templates
        $cats = [
            ['slug' => 'modern',  'display_name' => 'Modern',  'sort_order' => 1],
            ['slug' => 'classic', 'display_name' => 'Classic', 'sort_order' => 2],
            ['slug' => 'minimal', 'display_name' => 'Minimal', 'sort_order' => 3],
        ];

        foreach ($cats as $c) {
            $invTemCat = DB::table('invoice_template_categories')->updateOrInsert(
                ['slug' => $c['slug']],
                [
                    'display_name' => $c['display_name'],
                    'sort_order'   => $c['sort_order'],
                    'is_active'    => true,
                    'metadata'     => json_encode([]),
                    'updated_at'   => $now,
                    'created_at'   => $now,
                ]
            );
        }

        $classicId = DB::table('invoice_template_categories')->where('slug', 'classic')->value('id');
        $modernId  = DB::table('invoice_template_categories')->where('slug', 'modern')->value('id');
        $minimalId = DB::table('invoice_template_categories')->where('slug', 'minimal')->value('id');

        // Backfill existing templates by slug heuristic (adjust as needed)
        // e.g., your earlier seeded 'classic' and 'modern'
				// ----------------------------------------------------------------------------
				// Moderno Template
				// ----------------------------------------------------------------------------
				InvoiceTemplates::updateOrCreate([
					'invoice_template_category_id' => $modernId,
					'slug' => 'moderno',
					'display_name' => 'Moderno',
					'current_version' => 1,
					'is_active' => 1,
					'view' => 'modern.v1.moderno'
				]);
				InvoiceTemplates::updateOrCreate([
					'invoice_template_category_id' => $modernId,
					'slug' => 'neo',
					'display_name' => 'Neo',
					'current_version' => 1,
					'is_active' => 1,
					'view' => 'modern.v1.neo-columns'
				]);
				// ----------------------------------------------------------------------------
				// Classic
				// ----------------------------------------------------------------------------
				InvoiceTemplates::updateOrCreate([
					'invoice_template_category_id' => $classicId,
					'slug' => 'aurora',
					'display_name' => 'Aurora',
					'current_version' => 1,
					'is_active' => 1,
					'view' => 'classic.v1.aurora'
				]);
				InvoiceTemplates::updateOrCreate([
					'invoice_template_category_id' => $classicId,
					'slug' => 'ledger',
					'display_name' => 'Ledger',
					'current_version' => 1,
					'is_active' => 1,
					'view' => 'classic.v1.ledger'
				]);
				// ----------------------------------------------------------------------------
				// Minimal
				// ----------------------------------------------------------------------------
				InvoiceTemplates::updateOrCreate([
					'invoice_template_category_id' => $minimalId,
					'slug' => 'nexxus',
					'display_name' => 'Nexxus',
					'current_version' => 1,
					'is_active' => 1,
					'view' => 'minimal.v1.nexxus'
				]);

//        DB::table('invoice_templates')->where('slug', 'classic')->update([
//            'invoice_template_category_id' => $classicId,
//            'updated_at' => $now,
//        ]);
//
//        DB::table('invoice_templates')->where('slug', 'modern')->update([
//            'invoice_template_category_id' => $modernId,
//            'updated_at' => $now,
//        ]);

        // If you later add a 'minimal-*' family, map them, e.g.:
        // DB::table('invoice_templates')->where('slug', 'like', 'minimal%')->update([
        //     'invoice_template_category_id' => $minimalId,
        //     'updated_at' => $now,
        // ]);
			ColorScheme::truncate();
			ColorSchemeColor::truncate();
			$colorScheme = [
				[
					'color_scheme_name' => 'Ocean Blue',
					'slug' => 'ocean',
				],
				[
					'color_scheme_name' => 'Forest Green',
					'slug' => 'forest',
				],
				[
					'color_scheme_name' => 'Royal Purple',
					'slug' => 'royal',
				],
				[
					'color_scheme_name' => 'Crimson Red',
					'slug' => 'crimson',
				],
				[
					'color_scheme_name' => 'Sunset Orange',
					'slug' => 'sunset',
				]
			];
			foreach ($colorScheme as $c) {
				ColorScheme::updateOrCreate($c);
			}
			$royalId = ColorScheme::where('slug', 'royal')->value('id');
			$royalColorScheme = [
				[
					'color_scheme_id' => $royalId,
					'name' => 'main',
					'code' => '#8B5CF6',
				],
				[
					'color_scheme_id' => $royalId,
					'name' => 'light',
					'code' => '#D8B4FE',
				],
				[
					'color_scheme_id' => $royalId,
					'name' => 'extra_light',
					'code' => 'rgba(253, 242, 248, 0.3)',
				],
				[
					'color_scheme_id' => $royalId,
					'name' => 'gradient_bg_1',
					'code' => '90deg,rgba(147, 51, 234, 1) 0%, rgba(168, 85, 247, 0.67) 55%, rgba(236, 72, 153, 1) 100%',
				],
				[
					'color_scheme_id' => $royalId,
					'name' => 'table_tbody_color',
					'code' => '#FDF2F8',
				],
				[
					'color_scheme_id' => $royalId,
					'name' => 'gradient_bg_1_light',
					'code' => '142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%',
				],
			];
			$oceanId = ColorScheme::where('slug', 'ocean')->value('id');
			$oceanColorScheme = [
				[
					'color_scheme_id' => $oceanId,
					'name' => 'main',
					'code' => '#3B82F6',
				],
				[
					'color_scheme_id' => $oceanId,
					'name' => 'light',
					'code' => '#93C5FD',
				],
				[
					'color_scheme_id' => $oceanId,
					'name' => 'extra_light',
					'code' => 'rgba(255, 255, 255, 0.3)',
				],
				[
					'color_scheme_id' => $oceanId,
					'name' => 'gradient_bg_1',
					'code' => '90deg,#020024 0%, #090979 35%, #00D4FF 100%',
				],
				[
					'color_scheme_id' => $oceanId,
					'name' => 'table_tbody_color',
					'code' => '',
				],
				[
					'color_scheme_id' => $oceanId,
					'name' => 'gradient_bg_1_light',
					'code' => '142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%',
				],
			];
			$forestColorSchemeId = ColorScheme::where('slug', 'forest')->value('id');
			$forestColorScheme = [
				[
					'color_scheme_id' => $forestColorSchemeId,
					'name' => 'main',
					'code' => '#22C55E',
				],
				[
					'color_scheme_id' => $forestColorSchemeId,
					'name' => 'light',
					'code' => '#86EFAC',
				],
				[
					'color_scheme_id' => $forestColorSchemeId,
					'name' => 'extra_light',
					'code' => 'rgba(255, 255, 255, 0.3)',
				],
				[
					'color_scheme_id' => $forestColorSchemeId,
					'name' => 'gradient_bg_1',
					'code' => '90deg,#2A7B9B 0%, #57C785 50%, #EDDD53 100%',
				],
				[
					'color_scheme_id' => $forestColorSchemeId,
					'name' => 'table_tbody_color',
					'code' => '',
				],
				[
					'color_scheme_id' => $forestColorSchemeId,
					'name' => 'gradient_bg_1_light',
					'code' => '142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%',
				],
			];
			$crimsonColorSchemeId = ColorScheme::where('slug', 'crimson')->value('id');
			$crimsonColorScheme = [
				[
					'color_scheme_id' => $crimsonColorSchemeId,
					'name' => 'main',
					'code' => '#EF4444',
				],
				[
					'color_scheme_id' => $crimsonColorSchemeId,
					'name' => 'light',
					'code' => '#FCA5A5',
				],
				[
					'color_scheme_id' => $crimsonColorSchemeId,
					'name' => 'extra_light',
					'code' => 'rgba(255, 255, 255, 0.3)',
				],
				[
					'color_scheme_id' => $crimsonColorSchemeId,
					'name' => 'gradient_bg_1',
					'code' => '90deg,rgba(253, 29, 29, 1) 0%, rgba(252, 176, 69, 0.67) 55%, rgba(235, 143, 143, 1) 79%',
				],
				[
					'color_scheme_id' => $crimsonColorSchemeId,
					'name' => 'table_tbody_color',
					'code' => '',
				],
				[
					'color_scheme_id' => $crimsonColorSchemeId,
					'name' => 'gradient_bg_1_light',
					'code' => '',
				],
			];
			$sunsetColorSchemeId = ColorScheme::where('slug', 'sunset')->value('id');
			$sunsetColorScheme = [
				[
					'color_scheme_id' => $sunsetColorSchemeId,
					'name' => 'main',
					'code' => '#F97316',
				],
				[
					'color_scheme_id' => $sunsetColorSchemeId,
					'name' => 'light',
					'code' => '#FDBA74',
				],
				[
					'color_scheme_id' => $sunsetColorSchemeId,
					'name' => 'extra_light',
					'code' => 'rgba(255, 255, 255, 0.3)',
				],
				[
					'color_scheme_id' => $sunsetColorSchemeId,
					'name' => 'gradient_bg_1',
					'code' => '90deg,rgba(249, 115, 22, 1) 0%, rgba(252, 242, 242, 1) 55%, rgba(255, 237, 213, 1) 79%',
				],
				[
					'color_scheme_id' => $sunsetColorSchemeId,
					'name' => 'table_tbody_color',
					'code' => '',
				],
				[
					'color_scheme_id' => $sunsetColorSchemeId,
					'name' => 'gradient_bg_1_light',
					'code' => '142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%',
				],
			];

			$newColorScheme = array_merge($royalColorScheme, $oceanColorScheme, $forestColorScheme, $crimsonColorScheme, $sunsetColorScheme);
			foreach($newColorScheme as $colorScheme) {
				ColorSchemeColor::updateOrCreate($colorScheme);
			}
    }

    /**
     * Revert the database seeds.
     */
    public function revert(): void
    {
        //
    }
}
