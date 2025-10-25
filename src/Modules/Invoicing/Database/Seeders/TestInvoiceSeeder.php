<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Database\Seeders;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\BusinessProfiles;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Clients;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\ColorScheme;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceItems;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceTemplates;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use BilliftySDK\SharedResources\SDK\Database\MakeSeeder;

class TestInvoiceSeeder extends MakeSeeder
{
		protected $businessProfile = [
			[
				'name' => 'Test Company LLC',
				'legal_name' => 'Test Company LLC',
				'email' => 'test_company_llc@gmail.com',
				'phone' => '87365245111',
				'website' => '',
				'tax_id' => '',
				'license_no' => '',
				'address_line1' => '129 Bernham street',
				'city' => 'Houston',
				'state' => 'TX',
				'postal_code' => '1222',
				'country' => 'US',
			],
			[
				'name' => 'ILLCity Clothing LLC',
				'legal_name' => 'ILLCity Clothing LLC',
				'email' => 'illCityClothing@gmail.com',
				'phone' => '87365245311',
				'website' => '',
				'tax_id' => '',
				'license_no' => '',
				'address_line1' => '7099 Blair Stone Rd',
				'city' => 'Tallahasse',
				'state' => 'FL',
				'postal_code' => '32301',
				'country' => 'US',
			]
		];

		protected $clients = [
			[
				'name' => 'John Doe',
				'company' => 'EvaSoft LLC',
				'email' => 'johndoe@gmail.com',
				'phone' => '9876381234',
				'tax_id' => '',
				'license_no' => '',
				'address_line1' => '7900 S Post Oak',
				'city' => 'Houston',
				'state' => 'TX',
				'postal_code' => '77890',
				'country' => 'US',
				'is_test' => 1
			],
			[
				'name' => 'Harry Doe',
				'company' => 'Wee LLC',
				'email' => 'harry@gmail.com',
				'phone' => '9876316234',
				'tax_id' => '',
				'license_no' => '',
				'address_line1' => '1922 Pleasant Groove Rd',
				'city' => 'Houston',
				'state' => 'TX',
				'postal_code' => '77840',
				'country' => 'US',
				'is_test' => 1
			]
		];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
			$user = User::where('email', 'fordbedia@billifty.com')->first();
			foreach($this->businessProfile as $businessProfile) {
				BusinessProfiles::updateOrCreate(array_merge($businessProfile, ['user_id' => $user->id]));
			}

			foreach($this->clients as $client) {
				Clients::updateOrCreate(array_merge($client, ['user_id' => $user->id]));
			}
			$businessProfile = BusinessProfiles::where('email', 'test_company_llc@gmail.com')->first();
			$client = Clients::where('name', 'John Doe')->first();
			$invoiceTemplate = InvoiceTemplates::where('slug', 'moderno')->first();
			$colorScheme = ColorScheme::where('slug', 'ocean')->first();
			$invoice = Invoices::updateOrCreate([
				'business_profile_id' => $businessProfile->id,
				'client_id' => $client->id,
			],[
				'user_id' => $user->id,
				'invoice_template_id' => $invoiceTemplate->id,
				'color_scheme_id' => $colorScheme->id,
				'invoice_number' => 'INV-0001',
				'currency' => 'USD',
				'template_slug' => 'test-company-llc',
				'template_version' => 1
			]);

			InvoiceItems::updateOrCreate([
				'invoice_id' => $invoice->id,
				'position' => '1',
				'name' => 'User Login Authentication',
				'description' => 'Create a functinality for the user where they all be needed for verification before they proceed.',
				'quantity' => 1
			]);
			InvoiceItems::updateOrCreate([
				'invoice_id' => $invoice->id,
				'position' => '1',
				'name' => 'Landing Page Design',
				'description' => 'Home Page Design',
				'quantity' => 2
			]);
			InvoiceItems::updateOrCreate([
				'invoice_id' => $invoice->id,
				'position' => '1',
				'name' => 'Logo Design',
				'description' => 'Logo Design',
				'quantity' => 2
			]);

    }

    /**
     * Revert the database seeds.
     */
    public function revert(): void
    {
        //
    }
}
