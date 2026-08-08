<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Tests\Infrastructure\SQL;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL\SqlFilterRelation;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL\SqlLogicalFieldOperator;
use BilliftySDK\SharedResources\TestCase\BaseTest;

class SqlFilterRelationTest extends BaseTest
{
	public function test_make_creates_an_instance_with_meta_key_join_key_and_fields(): void
	{
		$nameField = SqlLogicalFieldOperator::where(colKey: 'c.name', bindings: 'Cris');

		$relation = SqlFilterRelation::make('client', 'c', ['name' => $nameField]);

		$this->assertInstanceOf(SqlFilterRelation::class, $relation);
		$this->assertSame('client', $relation->metaKey);
		$this->assertSame('c', $relation->joinKey);
		$this->assertSame(['name' => $nameField], $relation->fields);
	}

	public function test_meta_key_returns_the_configured_meta_key(): void
	{
		$relation = SqlFilterRelation::make('business_profile', 'bp');

		$this->assertSame('business_profile', $relation->metaKey());
	}

	public function test_field_returns_null_when_sub_field_is_not_defined(): void
	{
		$relation = SqlFilterRelation::make('client', 'c', [
			'name' => SqlLogicalFieldOperator::where(colKey: 'c.name', bindings: 'Cris'),
		]);

		$this->assertNull($relation->field('unknown'));
	}

	/**
	 * `SqlFilterRelation::field()` is declared to return `?SqlFilterFieldDefinition`, but that class
	 * does not exist anywhere in shared-resources and nothing in the codebase currently calls
	 * `field()` with a sub field that resolves to a non-null value. Per the AdvancedFilter module
	 * rules we do not fix this in place — this test only pins the current (broken) behavior so a
	 * future change to `fields` doesn't silently start returning a value here without anyone
	 * noticing the missing class.
	 */
	public function test_field_throws_when_a_defined_sub_field_is_resolved(): void
	{
		$relation = SqlFilterRelation::make('client', 'c', [
			'name' => SqlLogicalFieldOperator::where(colKey: 'c.name', bindings: 'Cris'),
		]);

		$this->expectException(\Error::class);
		$this->expectExceptionMessageMatches('/SqlFilterFieldDefinition/');

		$relation->field('name');
	}
}
