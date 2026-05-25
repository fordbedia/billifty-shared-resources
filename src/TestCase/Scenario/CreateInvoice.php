<?php

namespace BilliftySDK\SharedResources\TestCase\Scenario;

use BilliftySDK\SharedResources\TestCase\Builders\BusinessProfileBuilder;
use BilliftySDK\SharedResources\TestCase\Builders\ClientBuilder;
use BilliftySDK\SharedResources\TestCase\Builders\ColorSchemeBuilder;
use BilliftySDK\SharedResources\TestCase\Builders\CurrencyBuilder;
use BilliftySDK\SharedResources\TestCase\Builders\InvoiceBuilder;
use BilliftySDK\SharedResources\TestCase\Builders\InvoiceItemsBuilder;
use BilliftySDK\SharedResources\TestCase\Builders\InvoiceTemplateBuilder;
use BilliftySDK\SharedResources\TestCase\Builders\InvoiceTemplateCategoryBuilder;
use BilliftySDK\SharedResources\TestCase\Builders\PlanBuilder;
use BilliftySDK\SharedResources\TestCase\Builders\UserBuilder;
use BilliftySDK\SharedResources\TestCase\Builders\WorkspaceBuilder;
use Illuminate\Support\Facades\Auth;

class CreateInvoice extends BaseScenario
{
	public function handle(): TestScenarioCollection
	{
		$plan = PlanBuilder::make()->{$this->planType}();
		$user = UserBuilder::make($plan)->create();
		$workspace = $user->defaultWorkspace;
		$businessProfile = BusinessProfileBuilder::make($user)->create();
		$client = ClientBuilder::make($user)->create();
		$invoiceTemplateCategory = InvoiceTemplateCategoryBuilder::make()->createModernCategory();
		$invoiceTemplate = InvoiceTemplateBuilder::make($invoiceTemplateCategory)->createInvoiceTemplateModerno();
		$colorScheme = ColorSchemeBuilder::make()->createColorSchemeOcean();
		$currency = CurrencyBuilder::make('usd', 'USD', '$')->create();

		Auth::login($user);

		$invoice = InvoiceBuilder::make(
			workspace: $workspace,
			businessProfile: $businessProfile,
			client: $client,
			template: $invoiceTemplate,
			colorScheme: $colorScheme,
			currency: $currency,
			invoiceNumber: 'INV-00001',
			status: $this->status,
		)->addItems([
			new InvoiceItemsBuilder(
				position: 1,
				description: 'Logo Design',
				quantity: 2,
				unitPriceCents: 20000
			),
			new InvoiceItemsBuilder(
				position: 2,
				description: 'Web App Design',
				quantity: 2,
				unitPriceCents: 210000
			)
		])->create();

		return $this->collect(compact('plan', 'user', 'workspace', 'businessProfile', 'client', 'invoice'));
	}
}
