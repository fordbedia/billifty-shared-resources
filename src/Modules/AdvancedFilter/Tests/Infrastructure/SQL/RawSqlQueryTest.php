<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Tests\Infrastructure\SQL;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL\RawSqlQuery;
use BilliftySDK\SharedResources\TestCase\BaseTest;

class RawSqlQueryTest extends BaseTest
{
	public function test_constructor_defaults_bindings_to_empty_array(): void
	{
		$query = new RawSqlQuery('select 1');

		$this->assertSame('select 1', $query->sql);
		$this->assertSame([], $query->bindings);
	}

	public function test_make_creates_an_instance_with_sql_and_bindings(): void
	{
		$query = RawSqlQuery::make('select * from invoices where id = ?', [1]);

		$this->assertInstanceOf(RawSqlQuery::class, $query);
		$this->assertSame('select * from invoices where id = ?', $query->sql);
		$this->assertSame([1], $query->bindings);
	}

	public function test_append_concatenates_sql_with_a_leading_space_and_merges_bindings(): void
	{
		$query = RawSqlQuery::make('select * from invoices where 1 = 1', [1]);

		$query->append('and status = ?', ['draft']);

		$this->assertSame('select * from invoices where 1 = 1 and status = ?', $query->sql);
		$this->assertSame([1, 'draft'], $query->bindings);
	}

	public function test_append_can_be_called_without_bindings(): void
	{
		$query = RawSqlQuery::make('select * from invoices');

		$query->append('order by id desc');

		$this->assertSame('select * from invoices order by id desc', $query->sql);
		$this->assertSame([], $query->bindings);
	}
}
