<?php

namespace BilliftySDK\SharedResources\TestCase\Concerns;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceTemplateCategory;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceTemplates;

trait CreateInvoiceTemplateRecords
{
	public function createInvoiceTemplateModerno(?InvoiceTemplateCategory $category = null)
	{
		return InvoiceTemplates::create([
			'invoice_template_category_id' => $category?->id ?? $this->category->id,
			'slug' => 'modernoo',
			'display_name' => 'Moderno',
			'current_version' => 1,
			'preview_url' => '/images/templates/moderno.jpg',
			'is_active' => 1,
			'view' => 'modern.v1.moderno'
		]);
	}

	public function createInvoiceTemplateNeo(?InvoiceTemplateCategory $category = null)
	{
		return InvoiceTemplates::create([
			'invoice_template_category_id' => $category?->id ?? $this->category->id,
			'slug' => 'neo',
			'display_name' => 'Neo',
			'current_version' => 1,
			'preview_url' => '/images/templates/neo.jpg',
			'is_active' => 1,
			'view' => 'modern.v1.neo-columns'
		]);
	}

	public function createInvoiceTemplateMono(?InvoiceTemplateCategory $category = null)
	{
		return InvoiceTemplates::create([
			'invoice_template_category_id' => $category?->id ?? $this->category->id,
			'slug' => 'mono',
			'display_name' => 'Mono',
			'current_version' => 1,
			'preview_url' => '/images/templates/mono.jpg',
			'is_active' => 1,
			'view' => 'modern.v1.mono'
		]);
	}

	public function createInvoiceTemplateAurora(?InvoiceTemplateCategory $category = null)
	{
		return InvoiceTemplates::create([
			'invoice_template_category_id' => $category?->id ?? $this->category->id,
			'slug' => 'aurora',
			'display_name' => 'Aurora',
			'current_version' => 1,
			'preview_url' => '/images/templates/aurora.jpg',
			'is_active' => 1,
			'view' => 'classic.v1.aurora'
		]);
	}

	public function createInvoiceTemplateLedger(?InvoiceTemplateCategory $category = null)
	{
		return InvoiceTemplates::create([
			'invoice_template_category_id' => $category?->id ?? $this->category->id,
			'slug' => 'ledger',
			'display_name' => 'Ledger',
			'current_version' => 1,
			'preview_url' => '/images/templates/ledger.jpg',
			'is_active' => 1,
			'view' => 'classic.v1.ledger'
		]);
	}

	public function createInvoiceTemplateSimplifi(?InvoiceTemplateCategory $category = null)
	{
		return InvoiceTemplates::create([
			'invoice_template_category_id' => $category?->id ?? $this->category->id,
			'slug' => 'simplifi',
			'display_name' => 'Simplifi',
			'current_version' => 1,
			'preview_url' => '/images/templates/simplifi.jpg',
			'is_active' => 1,
			'view' => 'classic.v1.simplifi'
		]);
	}

	public function createInvoiceTemplateNexxus(?InvoiceTemplateCategory $category = null)
	{
		return InvoiceTemplates::create([
			'invoice_template_category_id' => $category?->id ?? $this->category->id,
			'slug' => 'nexxus',
			'display_name' => 'Nexxus',
			'current_version' => 1,
			'preview_url' => '/images/templates/nexxus.jpg',
			'is_active' => 1,
			'view' => 'minimal.v1.nexxus'
		]);
	}

	public function createInvoiceTemplatePulse(?InvoiceTemplateCategory $category = null)
	{
		return InvoiceTemplates::create([
			'invoice_template_category_id' => $category?->id ?? $this->category->id,
			'slug' => 'pulse',
			'display_name' => 'Pulse',
			'current_version' => 1,
			'preview_url' => '/images/templates/pulse.jpg',
			'is_active' => 1,
			'view' => 'minimal.v1.pulse'
		]);
	}
}