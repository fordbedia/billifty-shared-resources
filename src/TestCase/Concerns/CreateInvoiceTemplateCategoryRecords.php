<?php

namespace BilliftySDK\SharedResources\TestCase\Concerns;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceTemplateCategory;

trait CreateInvoiceTemplateCategoryRecords
{
	public function createModernCategory()
	{
		return InvoiceTemplateCategory::create([
			'slug' => 'modernn',
			'display_name' => 'Modern',
			'preview_url' => '/images/invoice-selection/modern.png',
			'sort_order' => 1,
			'is_active' => 1
		]);
	}

	public function createClassicCategory()
	{
		return InvoiceTemplateCategory::create([
			'slug' => 'classic',
			'display_name' => 'Classic',
			'preview_url' => '/images/invoice-selection/classic.png',
			'sort_order' => 1,
			'is_active' => 1
		]);
	}

	public function createMinimalCategory()
	{
		return InvoiceTemplateCategory::create([
			'slug' => 'minimal',
			'display_name' => 'Minimal',
			'preview_url' => '/images/invoice-selection/minimal.png',
			'sort_order' => 1,
			'is_active' => 1
		]);
	}
}