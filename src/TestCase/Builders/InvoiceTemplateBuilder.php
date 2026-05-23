<?php

namespace BilliftySDK\SharedResources\TestCase\Builders;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceTemplateCategory;
use BilliftySDK\SharedResources\TestCase\Concerns\CreateInvoiceTemplateRecords;

class InvoiceTemplateBuilder
{
	use CreateInvoiceTemplateRecords;

	public function __construct(protected InvoiceTemplateCategory $category)
	{}

	public static function make(InvoiceTemplateCategory $category)
	{
		return new self($category);
	}
}