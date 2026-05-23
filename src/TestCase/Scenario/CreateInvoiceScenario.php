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

class CreateInvoiceScenario
{
	public static function make(): array
	{
		$plan = PlanBuilder::make()->createFreePlan();
		$user = UserBuilder::make($plan)->create();
		$workspace = WorkspaceBuilder::make($user)->create();
		$businessProfile = BusinessProfileBuilder::make($user)->create();
		$client = ClientBuilder::make($user)->create();
		$invoiceTemplateCategory = InvoiceTemplateCategoryBuilder::make()->createModernCategory();
		$invoiceTemplate = InvoiceTemplateBuilder::make($invoiceTemplateCategory)->createInvoiceTemplateModerno();
		$colorScheme = ColorSchemeBuilder::make()->createColorSchemeOcean();
		$currency = CurrencyBuilder::make('usd', 'USD', '$')->create();

		$invoice = InvoiceBuilder::make(
			workspace: $workspace,
			businessProfile: $businessProfile,
			client: $client,
			template: $invoiceTemplate,
			colorScheme: $colorScheme,
			currency: $currency,
			invoiceNumber: 'INV-00001',
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

		return compact('plan', 'user', 'workspace', 'businessProfile', 'client', 'invoice');
	}
}
