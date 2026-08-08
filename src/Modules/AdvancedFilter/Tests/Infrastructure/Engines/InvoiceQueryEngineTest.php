<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Tests\Infrastructure\Engines;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Engines\AdvancedFilterQueryProcessor;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Engines\InvoiceQueryEngine;
use BilliftySDK\SharedResources\TestCase\BaseTest;

class InvoiceQueryEngineTest extends BaseTest
{
	public function test_invoice_draft_filter_targets_invoice_status(): void
	{
		$engine = new InvoiceQueryEngine(new AdvancedFilterQueryProcessor());
		$field = $engine->fields([
			'field' => 'invoice',
			'subField' => '',
			'operator' => null,
			'value' => 'draft',
		])['invoice'];

		$where = ($field->sql)($field->bindings);

		$this->assertSame('i.status = ?', $where['sql']);
		$this->assertSame(['draft'], $where['bindings']);
	}
}
