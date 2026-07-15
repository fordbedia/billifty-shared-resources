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
	private ?RawSqlQuery $compiledQuery = null;

	public function __construct()
	{
	}

	private function compileRawSql(string $sql, mixed $bindings): RawSqlQuery
	{
		if ($bindings === null || $bindings === []) {
			return RawSqlQuery::make($sql);
		}

		$bindings = is_array($bindings) ? $bindings : [$bindings];
		$compiledBindings = [];

		foreach ($bindings as $binding) {
			if (is_array($binding)) {
				$placeholders = implode(', ', array_fill(0, count($binding), '?'));
				$sql = preg_replace('/\?/', "({$placeholders})", $sql, 1);
				$compiledBindings = array_merge($compiledBindings, $binding);
				continue;
			}

			$compiledBindings[] = $binding;
		}

		return RawSqlQuery::make($sql, $compiledBindings);
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

	public function build(QueryEngine $definition, $input): RawSqlQuery
	{
		if (empty($input->groups)) {
			$joins = $this->mergeJoins($definition);
			$requiredJoinAliases = $this->joinAliasesFromSql($definition->baseSql()->sql);
			$this->compiledQuery = $this->compileQuery($definition, $joins, [], $requiredJoinAliases);

			return $this->compiledQuery;
		}

		$joins = $this->mergeJoins($definition);
		$whereGroups = [];
		$requiredJoinAliases = [];
		$bindings = [];
		$trackFields = [];

		foreach ($input->groups['groups'] as $i => $group) {
			foreach ($group['conditions'] as $item) {

				$field = $definition->fields($item)[$item['field']] ?? null;
				$trackFields[] = $item['field'];
				if ($field instanceof SqlLogicalFieldOperator && !$item['subField'] && $this->canRegisterField($field)) {
					$requiredJoinAliases = $this->appendRequiredJoinAliases($requiredJoinAliases, $field->dependentOn);
					$whereGroups[$i][] = $this->compileField($field, $item);
				}
				// ----------------------------------------------------------------------------
				// Relationship
				// ----------------------------------------------------------------------------
				if ($field instanceof SqlFilterRelation && $item['subField']) {
					$subField = $field->fields[$item['subField']] ?? null;
					if ($subField && $this->canRegisterField($subField)) {
						$requiredJoinAliases = $this->appendRequiredJoinAliases($requiredJoinAliases, [$field->joinKey]);
						$requiredJoinAliases = $this->appendRequiredJoinAliases($requiredJoinAliases, $subField->dependentOn);
						$whereGroups[$i][] = $this->compileField($subField, $item);
					}
				}
			}
		}

		$requiredJoinAliases = $this->appendRequiredJoinAliases(
			$requiredJoinAliases,
			$this->joinAliasesFromSql($definition->baseSql()->sql)
		);

		$this->compiledQuery = $this->compileQuery($definition, $joins, $whereGroups, $requiredJoinAliases);

		return $this->compiledQuery;
	}

	/**
	 * @param array $whereGroups
	 * @return RawSqlQuery
	 */
	private function compileQuery(
		QueryEngine $definition,
		array $joins,
		array $whereGroups,
		array $requiredJoinAliases = [],
	): RawSqlQuery {
		$sql = $definition->baseSql()->sql;
		$bindings = [];
		$baseBindings = [...$definition->baseSql()->bindings];
		$joinSql = [];
		$joinBindings = [];

		foreach ($this->resolveJoinAliases($requiredJoinAliases, $joins) as $joinAlias) {
			if (!isset($joins[$joinAlias])) {
				continue;
			}

			$joinSql[] = $joins[$joinAlias]['sql'];
			$joinBindings = array_merge($joinBindings, $joins[$joinAlias]['bindings'] ?? []);
		}

		if (!empty($joinSql)) {
			$wherePosition = stripos($sql, ' where ');
			$compiledJoinSql = implode(' ', $joinSql);

			if ($wherePosition !== false) {
				$sql = substr($sql, 0, $wherePosition) . ' ' . $compiledJoinSql . substr($sql, $wherePosition);
				$bindings = array_merge($joinBindings, $baseBindings);
			} else {
				$sql .= ' ' . $compiledJoinSql;
				$bindings = array_merge($baseBindings, $joinBindings);
			}
		} else {
			$bindings = $baseBindings;
		}

		$compiledGroups = [];

		foreach($whereGroups as $group) {
			if (empty($group)) {
				continue;
			}

			$compiledConditions = [];

			foreach($group as $i => $innerGroup) {
				$compiledConditions[] = trim($innerGroup->sql);
				$bindings = array_merge($bindings, $innerGroup->bindings);
			}

			if (!empty($compiledConditions)) {
				$compiledGroups[] = '(' . implode(' AND ', $compiledConditions) . ')';
			}
		}

		if (!empty($compiledGroups)) {
			$sql .= (stripos($sql, ' where ') === false ? ' WHERE ' : ' AND ')
				. '(' . implode(' OR ', $compiledGroups) . ')';
		}

		return RawSqlQuery::make($sql, $bindings);
	}

	private function canRegisterField(SqlLogicalFieldOperator $field): bool
	{
		$columnAlias = $this->aliasFromColumn($field->colKey);

		return $columnAlias !== null
			&& is_array($field->dependentOn)
			&& in_array($columnAlias, $field->dependentOn, true);
	}

	private function aliasFromColumn(string $column): ?string
	{
		if (!str_contains($column, '.')) {
			return null;
		}

		return explode('.', $column, 2)[0] ?: null;
	}

	private function appendRequiredJoinAliases(array $joinAliases, ?array $dependentOn): array
	{
		foreach ($dependentOn ?? [] as $alias) {
			if ($alias === 'i' || in_array($alias, $joinAliases, true)) {
				continue;
			}

			$joinAliases[] = $alias;
		}

		return $joinAliases;
	}

	private function resolveJoinAliases(array $requiredJoinAliases, array $joins): array
	{
		$resolved = [];

		$visit = function (string $alias) use (&$visit, &$resolved, $joins): void {
			if ($alias === 'i' || in_array($alias, $resolved, true) || !isset($joins[$alias])) {
				return;
			}

			foreach ($this->joinAliasesFromSql($joins[$alias]['sql']) as $dependencyAlias) {
				if ($dependencyAlias !== $alias) {
					$visit($dependencyAlias);
				}
			}

			$resolved[] = $alias;
		};

		foreach ($requiredJoinAliases as $alias) {
			$visit($alias);
		}

		return $resolved;
	}

	private function joinAliasesFromSql(string $sql): array
	{
		preg_match_all('/\b([a-zA-Z_][a-zA-Z0-9_]*)\./', $sql, $matches);

		return array_values(array_unique(array_filter(
			$matches[1] ?? [],
			fn(string $alias) => $alias !== 'i',
		)));
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
				$bindings = $where['bindings'] ?? [];

				return $this->compileRawSql($where['sql'], $bindings);
			}
		}

		if (isset($field?->type) && $field?->type?->name === SqlLogicalOperatorType::WHEREIN->name) {
			$bindings = is_array($field->bindings) ? $field->bindings : [$field->bindings];
			$placeholders = implode(', ', array_fill(0, count($bindings), '?'));

			return RawSqlQuery::make(
				sql: " {$field->colKey} IN ({$placeholders})",
				bindings: $bindings,
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

	public function getCompiledQuery(): ?RawSqlQuery
	{
		return $this->compiledQuery;
	}

	public static function compileSqlQeury(RawSqlQuery $query): string
	{
		$sql = $query->sql;

		foreach ($query->bindings as $binding) {
			$sql = preg_replace('/\?/', self::quoteSqlBinding($binding), $sql, 1);
		}

		return $sql;
	}

	private static function quoteSqlBinding(mixed $binding): string
	{
		if (is_array($binding)) {
			return '(' . implode(', ', array_map(
				fn($value) => self::quoteSqlBinding($value),
				$binding,
			)) . ')';
		}

		if ($binding === null) {
			return 'NULL';
		}

		if (is_bool($binding)) {
			return $binding ? '1' : '0';
		}

		if (is_int($binding) || is_float($binding)) {
			return (string)$binding;
		}

		return "'" . str_replace("'", "''", (string)$binding) . "'";
	}

	public function toArray()
	{
		return [
			'sql' => $this->compiledQuery->sql,
			'bindings' => $this->compiledQuery->bindings,
		];
	}
}
