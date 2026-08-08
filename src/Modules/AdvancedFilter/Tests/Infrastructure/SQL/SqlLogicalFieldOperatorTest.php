<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Tests\Infrastructure\SQL;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Enums\SqlLogicalOperatorType;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL\SqlLogicalFieldOperator;
use BilliftySDK\SharedResources\TestCase\BaseTest;

class SqlLogicalFieldOperatorTest extends BaseTest
{
	public function test_condition_uses_bindings_argument_when_provided(): void
	{
		$field = (new SqlLogicalFieldOperator('i.status'))->condition(
			colKey: 'i.status',
			type: SqlLogicalOperatorType::WHERE,
			bindings: 'draft',
			value: 'ignored',
		);

		$this->assertSame('draft', $field->bindings);
	}

	public function test_condition_falls_back_to_value_when_bindings_is_null(): void
	{
		$field = (new SqlLogicalFieldOperator('i.status'))->condition(
			colKey: 'i.status',
			type: SqlLogicalOperatorType::WHERE,
			value: 'issued',
		);

		$this->assertSame('issued', $field->bindings);
		$this->assertSame('i.status', $field->colKey);
		$this->assertSame(SqlLogicalOperatorType::WHERE, $field->type);
	}

	public function test_as_raw_invokes_the_sql_callback_immediately_and_stores_it_as_a_closure(): void
	{
		$calls = [];
		$sql = function ($bindings) use (&$calls) {
			$calls[] = $bindings;

			return ['sql' => 'i.status = ?', 'bindings' => [$bindings]];
		};

		$field = (new SqlLogicalFieldOperator('i.status'))->asRaw(
			colKey: 'i.status',
			type: SqlLogicalOperatorType::RAW,
			sql: $sql,
			value: 'draft',
		);

		$this->assertSame(['draft'], $calls, 'asRaw() is expected to invoke the sql callback once during construction.');
		$this->assertSame(SqlLogicalOperatorType::RAW, $field->type);
		$this->assertSame('draft', $field->bindings);
		$this->assertInstanceOf(\Closure::class, $field->sql);
		$this->assertSame(['sql' => 'i.status = ?', 'bindings' => ['draft']], ($field->sql)('draft'));
	}

	public function test_operator_aware_where_builds_in_clause_for_contains(): void
	{
		$field = SqlLogicalFieldOperator::operatorAwareWhere(
			colKey: 'c.name',
			operator: 'contains',
			value: ['Cris', 'Jane'],
		);

		$where = ($field->sql)($field->bindings);

		$this->assertSame('c.name IN ?', $where['sql']);
		$this->assertSame([['Cris', 'Jane']], $where['bindings']);
	}

	public function test_operator_aware_where_builds_not_in_clause_for_not_contains(): void
	{
		$field = SqlLogicalFieldOperator::operatorAwareWhere(
			colKey: 'c.name',
			operator: 'not_contains',
			value: ['Cris'],
		);

		$where = ($field->sql)($field->bindings);

		$this->assertSame('c.name NOT IN ?', $where['sql']);
		$this->assertSame([['Cris']], $where['bindings']);
	}

	public function test_operator_aware_where_builds_like_clause_and_wraps_value_with_wildcards(): void
	{
		$field = SqlLogicalFieldOperator::operatorAwareWhere(
			colKey: 'c.email',
			operator: 'like',
			value: 'cris',
		);

		$where = ($field->sql)($field->bindings);

		$this->assertSame('c.email LIKE ?', $where['sql']);
		$this->assertSame(['%cris%'], $where['bindings']);
	}

	/**
	 * @dataProvider notEqualsOperatorAliases
	 */
	public function test_operator_aware_where_builds_not_equals_clause(string $operatorAlias): void
	{
		$field = SqlLogicalFieldOperator::operatorAwareWhere(
			colKey: 'c.email',
			operator: $operatorAlias,
			value: 'cris@example.com',
		);

		$where = ($field->sql)($field->bindings);

		$this->assertSame('c.email != ?', $where['sql']);
		$this->assertSame(['cris@example.com'], $where['bindings']);
	}

	public static function notEqualsOperatorAliases(): array
	{
		return [
			'!=' => ['!='],
			'not_equals' => ['not_equals'],
		];
	}

	public function test_operator_aware_where_builds_equals_clause(): void
	{
		$field = SqlLogicalFieldOperator::operatorAwareWhere(
			colKey: 'c.email',
			operator: '=',
			value: 'cris@example.com',
		);

		$where = ($field->sql)($field->bindings);

		$this->assertSame('c.email = ?', $where['sql']);
		$this->assertSame(['cris@example.com'], $where['bindings']);
	}

	public function test_operator_aware_where_defaults_to_in_clause_for_unknown_operator_with_array_value(): void
	{
		$field = SqlLogicalFieldOperator::operatorAwareWhere(
			colKey: 'c.email',
			operator: 'unsupported',
			value: ['a@example.com', 'b@example.com'],
		);

		$where = ($field->sql)($field->bindings);

		$this->assertSame('c.email IN ?', $where['sql']);
		$this->assertSame([['a@example.com', 'b@example.com']], $where['bindings']);
	}

	public function test_operator_aware_where_defaults_to_equals_clause_for_unknown_operator_with_scalar_value(): void
	{
		$field = SqlLogicalFieldOperator::operatorAwareWhere(
			colKey: 'c.email',
			operator: 'unsupported',
			value: 'cris@example.com',
		);

		$where = ($field->sql)($field->bindings);

		$this->assertSame('c.email = ?', $where['sql']);
		$this->assertSame(['cris@example.com'], $where['bindings']);
	}

	public function test_call_static_where_maps_to_where_type(): void
	{
		$field = SqlLogicalFieldOperator::where(colKey: 'i.status', dependentOn: ['i'], value: 'draft');

		$this->assertSame(SqlLogicalOperatorType::WHERE, $field->type);
		$this->assertSame('i.status', $field->colKey);
		$this->assertSame(['i'], $field->dependentOn);
		$this->assertSame('draft', $field->bindings);
	}

	public function test_call_static_where_in_maps_to_wherein_type(): void
	{
		$field = SqlLogicalFieldOperator::whereIn(colKey: 'i.status', dependentOn: ['i'], bindings: ['draft', 'issued']);

		$this->assertSame(SqlLogicalOperatorType::WHEREIN, $field->type);
		$this->assertSame(['draft', 'issued'], $field->bindings);
	}

	public function test_call_static_where_between_maps_to_wherebetween_type(): void
	{
		$field = SqlLogicalFieldOperator::whereBetween(
			colKey: 'i.created_at',
			dependentOn: ['i'],
			bindings: ['from' => '2026-01-01', 'to' => '2026-01-31'],
		);

		$this->assertSame(SqlLogicalOperatorType::WHEREBETWEEN, $field->type);
		$this->assertSame(['from' => '2026-01-01', 'to' => '2026-01-31'], $field->bindings);
	}

	public function test_call_static_with_raw_type_delegates_to_as_raw(): void
	{
		$field = SqlLogicalFieldOperator::where(
			colKey: 'i.status',
			type: SqlLogicalOperatorType::RAW,
			dependentOn: ['i'],
			value: 'draft',
			sql: fn ($value) => ['sql' => 'i.status = ?', 'bindings' => [$value]],
		);

		$this->assertSame(SqlLogicalOperatorType::RAW, $field->type);
		$this->assertInstanceOf(\Closure::class, $field->sql);
		$this->assertSame(['sql' => 'i.status = ?', 'bindings' => ['draft']], ($field->sql)('draft'));
	}
}
