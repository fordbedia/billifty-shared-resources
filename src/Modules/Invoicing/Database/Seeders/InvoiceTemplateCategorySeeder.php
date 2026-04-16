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
            ['slug' => 'modern',  'display_name' => 'Modern',  'sort_order' => 1, 'preview_url' => '/images/invoice-selection/modern.png'],
            ['slug' => 'classic', 'display_name' => 'Classic', 'sort_order' => 2, 'preview_url' => '/images/invoice-selection/classic.png'],
            ['slug' => 'minimal', 'display_name' => 'Minimal', 'sort_order' => 3, 'preview_url' => '/images/invoice-selection/minimal.png'],
        ];

        foreach ($cats as $c) {
            $invTemCat = DB::table('invoice_template_categories')->updateOrInsert(
                ['slug' => $c['slug']],
                [
					'preview_url' => $c['preview_url'],
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
		InvoiceTemplates::updateOrCreate(['slug' => 'moderno'],[
			'invoice_template_category_id' => $modernId,
			'slug' => 'moderno',
			'display_name' => 'Moderno',
			'current_version' => 1,
			'is_active' => 1,
			'view' => 'modern.v1.moderno',
			'preview_url' => '/images/templates/moderno.jpg',
		]);
		InvoiceTemplates::updateOrCreate(['slug' => 'neo'],[
			'invoice_template_category_id' => $modernId,
			'slug' => 'neo',
			'display_name' => 'Neo',
			'current_version' => 1,
			'is_active' => 1,
			'view' => 'modern.v1.neo-columns',
			'preview_url' => '/images/templates/neo.jpg',
		]);
		InvoiceTemplates::updateOrCreate(['slug' => 'mono'],[
			'invoice_template_category_id' => $modernId,
			'slug' => 'mono',
			'display_name' => 'Mono',
			'current_version' => 1,
			'is_active' => 1,
			'view' => 'modern.v1.mono',
			'preview_url' => '/images/templates/mono.jpg',
		]);
		// ----------------------------------------------------------------------------
		// Classic
		// ----------------------------------------------------------------------------
		InvoiceTemplates::updateOrCreate(['slug' => 'aurora'], [
			'invoice_template_category_id' => $classicId,
			'slug' => 'aurora',
			'display_name' => 'Aurora',
			'current_version' => 1,
			'is_active' => 1,
			'view' => 'classic.v1.aurora',
			'preview_url' => '/images/templates/aurora.jpg',
		]);
		InvoiceTemplates::updateOrCreate(['slug' => 'ledger'], [
			'invoice_template_category_id' => $classicId,
			'slug' => 'ledger',
			'display_name' => 'Ledger',
			'current_version' => 1,
			'is_active' => 1,
			'view' => 'classic.v1.ledger',
			'preview_url' => '/images/templates/ledger.jpg',
		]);
		InvoiceTemplates::updateOrCreate(['slug' => 'simplifi'], [
			'invoice_template_category_id' => $classicId,
			'slug' => 'simplifi',
			'display_name' => 'Simplifi',
			'current_version' => 1,
			'is_active' => 1,
			'view' => 'classic.v1.simplifi',
			'preview_url' => '/images/templates/simplifi.jpg',
		]);
		// ----------------------------------------------------------------------------
		// Minimal
		// ----------------------------------------------------------------------------
		InvoiceTemplates::updateOrCreate(['slug' => 'nexxus'], [
			'invoice_template_category_id' => $minimalId,
			'slug' => 'nexxus',
			'display_name' => 'Nexxus',
			'current_version' => 1,
			'is_active' => 1,
			'view' => 'minimal.v1.nexxus',
			'preview_url' => '/images/templates/nexxus.jpg',
		]);
		InvoiceTemplates::updateOrCreate(['slug' => 'pulse'], [
			'invoice_template_category_id' => $minimalId,
			'slug' => 'pulse',
			'display_name' => 'Pulse',
			'current_version' => 1,
			'is_active' => 1,
			'view' => 'minimal.v1.pulse',
			'preview_url' => '/images/templates/pulse.jpg',
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
			ColorScheme::query()->delete();
			ColorSchemeColor::query()->delete();
			$colorScheme = [
				[
					'color_scheme_name' => 'Ocean Blue',
					'slug' => 'ocean',
					'preview_url' => '/images/invoice-selection/ocean-blue.png',
				],
				[
					'color_scheme_name' => 'Forest Green',
					'slug' => 'forest',
					'preview_url' => '/images/invoice-selection/forest-green.png',
				],
				[
					'color_scheme_name' => 'Royal Purple',
					'slug' => 'royal',
					'preview_url' => '/images/invoice-selection/royal-purple.png',
				],
				[
					'color_scheme_name' => 'Crimson Red',
					'slug' => 'crimson',
					'preview_url' => '/images/invoice-selection/crimson-red.png',
				],
				[
					'color_scheme_name' => 'Sunset Orange',
					'slug' => 'sunset',
					'preview_url' => '/images/invoice-selection/sunset-orange.png',
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
					'code' => '142deg,rgba(249, 115, 22, 1) 1%, rgba(253, 186, 116, 1) 100%',
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
