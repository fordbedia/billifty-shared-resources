<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Engines;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\DTOs\AdvancedFilterInput;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Enums\SqlLogicalOperatorType;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Exceptions\InvalidAdvancedFilterInputException;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL\RawSqlQuery;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL\SqlFilterRelation;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL\SqlLogicalFieldOperator;

final class AdvancedFilterQueryProcessor
{
	public function __construct()
	{
	}

	private function sqlBindings(mixed $bindings): mixed
	{
		if (!is_array($bindings)) {
			return $bindings;
		}

		$quotedBindings = array_map(
			fn($binding) => "'" . str_replace("'", "''", (string)$binding) . "'",
			$bindings,
		);

		return '(' . implode(', ', $quotedBindings) . ')';
	}

	protected function mergeJoins(QueryEngine $definition): array
	{
		$main = [
			'u' => [
				'sql' => "inner join users u on u.id = ?",
				'bindings' => [auth()->id()]
			],
			'w' => [
				'sql' => 'inner join workspace w on w.user_id = u.id',
				'bindings' => [],
			],
		];

		return [...$main, ...$definition->joins()];
	}

	public function build(QueryEngine $definition, $input): void
	{
		if (empty($input->groups)) {
			return;
		}

		$joins = $this->mergeJoins($definition);
		$whereGroups = [];
		$bindings = [];
		$trackFields = [];
//		print_r($definition->fields()['invoice_number']['where']('sdsd'));
//		dd($definition->fields()['invoice_number']);
		foreach ($input->groups['groups'] as $i => $group) {
			foreach ($group['conditions'] as $item) {
//				dump($item);
				$field = $definition->fields($item)[$item['field']] ?? null;
				$trackFields[] = $item['field'];
				// Check if type if raw
				if (isset($field?->type) && $field?->type?->name === SqlLogicalOperatorType::RAW->name) {
					$function = $field?->sql;

					if ($function instanceof \Closure) {
						$where = $function($field->bindings);

						$whereGroups[$i][] = RawSqlQuery::make(
							sql: $where['sql'],
							bindings: [$this->sqlBindings($where['bindings'])]
						);
					}
				}
				// WhereIn
				if (isset($field?->type) && $field?->type?->name === SqlLogicalOperatorType::WHEREIN->name) {
					$whereGroups[$i][] = RawSqlQuery::make(
						sql: " {$field->colKey} IN ?",
						bindings: [$this->sqlBindings($field->bindings)],
					);
				}
				if (isset($field?->type) && $field?->type?->name === SqlLogicalOperatorType::WHEREBETWEEN->name) {
					if (!is_array($field->bindings)) {
						throw InvalidAdvancedFilterInputException::invalidValueType(
							field: $item['field'],
							expectedType: 'array',
							value: $field->bindings,
						);
					}
					$whereGroups[$i][] = RawSqlQuery::make(
						sql: " {$field->colKey} BETWEEN ? AND ?",
						bindings: [
							$field->bindings['from'] ?? null,
							$field->bindings['to'] ?? null,
						]);
				}
				// @TODO: Work on the relationship
				dump($item);
				if ($field instanceof SqlFilterRelation && $item['subField']) {
					$subField = $field->fields[$item['subField']] ?? null;

					if ($subField) {
						$whereGroups[$i][] = $this->compileField($subField, $item);
					}
				}
			}
		}

		dd($whereGroups);
	}

	private function compileWhereCondition(string $column, mixed $value): RawSqlQuery
	{
		if (is_array($value)) {
			$placeholders = implode(', ', array_fill(0, count($value), '?'));

			return RawSqlQuery::make(
				sql: "{$column} IN ({$placeholders})",
				bindings: $value,
			);
		}

		return RawSqlQuery::make(
			sql: "{$column} = ?",
			bindings: [$value],
		);
	}

	private function compileField(SqlLogicalFieldOperator $field, array $item): RawSqlQuery
	{
		if (isset($field?->type) && $field?->type?->name === SqlLogicalOperatorType::RAW->name) {
			$function = $field?->sql;

			if ($function instanceof \Closure) {
				$where = $function($field->bindings);

				return RawSqlQuery::make(
					sql: $where['sql'],
					bindings: [$this->sqlBindings($where['bindings'])]
				);
			}
		}

		if (isset($field?->type) && $field?->type?->name === SqlLogicalOperatorType::WHEREIN->name) {
			return RawSqlQuery::make(
				sql: " {$field->colKey} IN ?",
				bindings: [$this->sqlBindings($field->bindings)],
			);
		}

		if (isset($field?->type) && $field?->type?->name === SqlLogicalOperatorType::WHEREBETWEEN->name) {
			if (!is_array($field->bindings)) {
				throw InvalidAdvancedFilterInputException::invalidValueType(
					field: $item['field'],
					expectedType: 'array',
					value: $field->bindings,
				);
			}

			return RawSqlQuery::make(
				sql: " {$field->colKey} BETWEEN ? AND ?",
				bindings: [
					$field->bindings['from'] ?? null,
					$field->bindings['to'] ?? null,
				]);
		}

		return $this->compileWhereCondition($field->colKey, $field->bindings);
	}

	private function countMultipleFields(array $fields, string $field): int
	{
		return count(array_filter($fields, fn($f) => $f === $field));
	}
}
