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
					'hex_color' => '#8B5CF6',
				],
				[
					'color_scheme_id' => $royalId,
					'name' => 'light',
					'hex_color' => '#D8B4FE',
				],
				[
					'color_scheme_id' => $royalId,
					'name' => 'extra_light',
					'hex_color' => '#ffffff4a',
				]
			];
			$oceanId = ColorScheme::where('slug', 'ocean')->value('id');
			$oceanColorScheme = [
				[
					'color_scheme_id' => $oceanId,
					'name' => 'main',
					'hex_color' => '#3B82F6',
				],
				[
					'color_scheme_id' => $oceanId,
					'name' => 'light',
					'hex_color' => '#93C5FD',
				],
				[
					'color_scheme_id' => $oceanId,
					'name' => 'extra_light',
					'hex_color' => '#ffffff4a',
				]
			];
			$forestColorSchemeId = ColorScheme::where('slug', 'forest')->value('id');
			$forestColorScheme = [
				[
					'color_scheme_id' => $forestColorSchemeId,
					'name' => 'main',
					'hex_color' => '#22C55E',
				],
				[
					'color_scheme_id' => $forestColorSchemeId,
					'name' => 'light',
					'hex_color' => '#86EFAC',
				],
				[
					'color_scheme_id' => $forestColorSchemeId,
					'name' => 'extra_light',
					'hex_color' => '#ffffff4a',
				]
			];
			$crimsonColorSchemeId = ColorScheme::where('slug', 'crimson')->value('id');
			$crimsonColorScheme = [
				[
					'color_scheme_id' => $crimsonColorSchemeId,
					'name' => 'main',
					'hex_color' => '#EF4444',
				],
				[
					'color_scheme_id' => $crimsonColorSchemeId,
					'name' => 'light',
					'hex_color' => '#FCA5A5',
				],
				[
					'color_scheme_id' => $crimsonColorSchemeId,
					'name' => 'extra_light',
					'hex_color' => '#ffffff4a',
				]
			];
			$sunsetColorSchemeId = ColorScheme::where('slug', 'sunset')->value('id');
			$sunsetColorScheme = [
				[
					'color_scheme_id' => $sunsetColorSchemeId,
					'name' => 'main',
					'hex_color' => '#F97316',
				],
				[
					'color_scheme_id' => $sunsetColorSchemeId,
					'name' => 'light',
					'hex_color' => '#FDBA74',
				],
				[
					'color_scheme_id' => $sunsetColorSchemeId,
					'name' => 'extra_light',
					'hex_color' => '#ffffff4a',
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
