<?php

namespace BilliftySDK\SharedResources\TestCase\Builders;

use BilliftySDK\SharedResources\TestCase\Concerns\CreateInvoiceTemplateCategoryRecords;

class InvoiceTemplateCategoryBuilder
{
	use CreateInvoiceTemplateCategoryRecords;

	public static function make()
	{
		return new self;
	}
}