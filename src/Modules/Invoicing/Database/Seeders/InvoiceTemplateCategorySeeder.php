<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Database\Seeders;

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

				// Create categories
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

				$invoiceTemplate = InvoiceTemplates::updateOrCreate([
					'invoice_template_category_id' => $modernId,
					'slug' => 'moderno',
					'display_name' => 'Moderno',
					'current_version' => 1,
					'is_active' => 1
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
    }

    /**
     * Revert the database seeds.
     */
    public function revert(): void
    {
        //
    }
}
