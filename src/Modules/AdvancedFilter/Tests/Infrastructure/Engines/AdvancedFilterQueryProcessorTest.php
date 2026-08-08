<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Tests\Infrastructure\Engines;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\DTOs\AdvancedFilterInput;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Engines\AdvancedFilterQueryProcessor;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Engines\QueryEngine;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Enums\SqlLogicalOperatorType;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Exceptions\InvalidAdvancedFilterInputException;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL\RawSqlQuery;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL\SqlFilterRelation;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL\SqlLogicalFieldOperator;
use BilliftySDK\SharedResources\TestCase\BaseTest;

class AdvancedFilterQueryProcessorTest extends BaseTest
{
	protected AdvancedFilterQueryProcessor $processor;
	protected QueryEngine $definition;

	public function setUp(): void
	{
		parent::setUp();

		$this->processor = new AdvancedFilterQueryProcessor();
		$this->definition = new FakeAdvancedFilterQueryEngine();
	}

	public function test_build_with_empty_groups_returns_the_base_query_untouched(): void
	{
		$input = new AdvancedFilterInput([]);

		$query = $this->processor->build($this->definition, $input);

		$this->assertSame('select i.* from items i where i.tenant_id = ?', $query->sql);
		$this->assertSame([1], $query->bindings);
	}

	public function test_build_appends_a_single_condition_to_the_base_where_clause(): void
	{
		$input = new AdvancedFilterInput([
			'groups' => [
				[
					'conditions' => [
						['field' => 'status', 'subField' => null, 'operator' => null, 'value' => ['draft']],
					],
				],
			],
		]);

		$query = $this->processor->build($this->definition, $input);

		$this->assertSame(
			'select i.* from items i where i.tenant_id = ? AND ((i.status IN (?)))',
			$query->sql
		);
		$this->assertSame([1, 'draft'], $query->bindings);
	}

	public function test_build_ands_multiple_conditions_within_the_same_group(): void
	{
		$input = new AdvancedFilterInput([
			'groups' => [
				[
					'conditions' => [
						['field' => 'status', 'subField' => null, 'operator' => null, 'value' => ['draft']],
						['field' => 'amount', 'subField' => null, 'operator' => null, 'value' => ['from' => 10, 'to' => 20]],
					],
				],
			],
		]);

		$query = $this->processor->build($this->definition, $input);

		$this->assertSame(
			'select i.* from items i where i.tenant_id = ? AND ((i.status IN (?) AND i.amount BETWEEN ? AND ?))',
			$query->sql
		);
		$this->assertSame([1, 'draft', 10, 20], $query->bindings);
	}

	public function test_build_ors_separate_groups(): void
	{
		$input = new AdvancedFilterInput([
			'groups' => [
				['conditions' => [['field' => 'status', 'subField' => null, 'operator' => null, 'value' => ['draft']]]],
				['conditions' => [['field' => 'status', 'subField' => null, 'operator' => null, 'value' => ['issued']]]],
			],
		]);

		$query = $this->processor->build($this->definition, $input);

		$this->assertSame(
			'select i.* from items i where i.tenant_id = ? AND ((i.status IN (?)) OR (i.status IN (?)))',
			$query->sql
		);
		$this->assertSame([1, 'draft', 'issued'], $query->bindings);
	}

	public function test_build_ignores_conditions_for_fields_not_declared_by_the_definition(): void
	{
		$input = new AdvancedFilterInput([
			'groups' => [
				['conditions' => [['field' => 'unknown_field', 'subField' => null, 'operator' => null, 'value' => 'x']]],
			],
		]);

		$query = $this->processor->build($this->definition, $input);

		$this->assertSame('select i.* from items i where i.tenant_id = ?', $query->sql);
		$this->assertSame([1], $query->bindings);
	}

	public function test_build_skips_a_top_level_field_when_a_subfield_is_requested(): void
	{
		$input = new AdvancedFilterInput([
			'groups' => [
				['conditions' => [['field' => 'status', 'subField' => 'unexpected', 'operator' => null, 'value' => ['draft']]]],
			],
		]);

		$query = $this->processor->build($this->definition, $input);

		$this->assertSame('select i.* from items i where i.tenant_id = ?', $query->sql);
		$this->assertSame([1], $query->bindings);
	}

	public function test_build_resolves_transitive_join_dependencies_for_relation_subfields(): void
	{
		$input = new AdvancedFilterInput([
			'groups' => [
				['conditions' => [['field' => 'related', 'subField' => 'name', 'operator' => null, 'value' => 'Cris']]],
			],
		]);

		$query = $this->processor->build($this->definition, $input);

		$this->assertSame(
			'select i.* from items i '
			. 'inner join a_table a on a.item_id = i.id '
			. 'inner join b_table b on b.a_id = a.id '
			. 'where i.tenant_id = ? AND ((b.name = ?))',
			$query->sql
		);
		$this->assertSame([1, 'Cris'], $query->bindings);
	}

	public function test_build_throws_when_where_between_binding_is_not_an_array(): void
	{
		$input = new AdvancedFilterInput([
			'groups' => [
				['conditions' => [['field' => 'amount', 'subField' => null, 'operator' => null, 'value' => 'not-a-range']]],
			],
		]);

		$this->expectException(InvalidAdvancedFilterInputException::class);
		$this->expectExceptionMessage('Invalid advanced filter value for field [amount]. Expected [array], got [string].');

		$this->processor->build($this->definition, $input);
	}

	public function test_get_compiled_query_and_to_array_reflect_the_last_built_query(): void
	{
		$input = new AdvancedFilterInput([]);

		$query = $this->processor->build($this->definition, $input);

		$this->assertSame($query, $this->processor->getCompiledQuery());
		$this->assertSame(
			['sql' => $query->sql, 'bindings' => $query->bindings],
			$this->processor->toArray()
		);
	}

	public function test_compile_sql_qeury_interpolates_bindings_of_every_scalar_type_in_placeholder_order(): void
	{
		$query = RawSqlQuery::make(
			'select * from t where a = ? and b = ? and c = ? and d = ? and e = ? and f in ?',
			[null, true, false, 42, 3.5, ['x', "o'Brien"]],
		);

		$sql = AdvancedFilterQueryProcessor::compileSqlQeury($query);

		$this->assertSame(
			"select * from t where a = NULL and b = 1 and c = 0 and d = 42 and e = 3.5 and f in ('x', 'o''Brien')",
			$sql
		);
	}
}

class FakeAdvancedFilterQueryEngine implements QueryEngine
{
	public function baseSql(): RawSqlQuery
	{
		return RawSqlQuery::make('select i.* from items i where i.tenant_id = ?', [1]);
	}

	public function joins(): array
	{
		return [
			'a' => [
				'sql' => 'inner join a_table a on a.item_id = i.id',
				'bindings' => [],
			],
			'b' => [
				'sql' => 'inner join b_table b on b.a_id = a.id',
				'bindings' => [],
			],
		];
	}

	public function fields($item): array
	{
		$operator = is_string($item['operator'] ?? null) && $item['operator'] !== '' ? $item['operator'] : '=';

		return [
			'status' => SqlLogicalFieldOperator::whereIn(
				colKey: 'i.status',
				dependentOn: ['i'],
				bindings: $item['value'],
			),
			'amount' => SqlLogicalFieldOperator::whereBetween(
				colKey: 'i.amount',
				dependentOn: ['i'],
				bindings: $item['value'],
			),
			'related' => SqlFilterRelation::make(
				metaKey: 'related',
				joinKey: 'b',
				fields: [
					'name' => SqlLogicalFieldOperator::operatorAwareWhere(
						colKey: 'b.name',
						operator: $operator,
						dependentOn: ['b'],
						value: $item['value'],
					),
				],
			),
		];
	}

	public function search(AdvancedFilterInput $input, int $perPage = 15): RawSqlQuery
	{
		throw new \RuntimeException('Not used in AdvancedFilterQueryProcessorTest.');
	}
}
