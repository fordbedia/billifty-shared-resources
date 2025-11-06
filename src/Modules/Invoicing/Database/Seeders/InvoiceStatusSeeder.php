<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Database\Seeders;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use BilliftySDK\SharedResources\SDK\Database\MakeSeeder;

class InvoiceStatusSeeder extends MakeSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ----------------------------------------------------------------------------
		// Invoice Status
		// ----------------------------------------------------------------------------
		$statuses = [
			[
				'id' => 1,
				'name' => 'In Progress',
				'slug' => 'in-progress',
			],
			[
				'id' => 2,
				'name' => 'Draft',
				'slug' => 'draft',
			],
			[
				'id' => 3,
				'name' => 'Issued',
				'slug' => 'issued',
			],
			[
				'id' => 4,
				'name' => 'Sent',
				'slug' => 'sent',
			],
			[
				'id' => 5,
				'name' => 'Paid',
				'slug' => 'paid',
			],
			[
				'id' => 6,
				'name' => 'Partial',
				'slug' => 'partial',
			],
			[
				'id' => 7,
				'name' => 'Void',
				'slug' => 'void',
			]
		];
		foreach($statuses as $status) {
			InvoiceStatus::updateOrCreate($status);
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
