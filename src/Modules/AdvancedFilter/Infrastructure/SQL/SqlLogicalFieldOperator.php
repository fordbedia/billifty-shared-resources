<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Enums\SqlLogicalOperatorType;
use Closure;

final readonly class SqlLogicalFieldOperator
{
	public function __construct(
		public string                 $colKey,
		public ?SqlLogicalOperatorType $type = null,
		public ?array                $dependentOn = null,
		public mixed                  $bindings = null,
		public ?Closure               $sql = null,
	) {}

	public function asRaw(
		string                 $colKey,
		SqlLogicalOperatorType $type,
		?array                $dependentOn = null,
		?callable              $sql = null,
		mixed                  $bindings = null,
		mixed                  $value = null,
	): self {
		$bindings ??= $value;

		if ($sql) {
			$sql($bindings);
		}
		return new self($colKey, $type, $dependentOn, $bindings, $sql ? Closure::fromCallable($sql) : null);
	}

	public function condition(
		string                 $colKey,
		?SqlLogicalOperatorType $type = null,
		?array                $dependentOn = null,
		mixed                  $bindings = null,
		mixed                  $value = null,
	): self {
		$bindings ??= $value;

		return new self($colKey, $type, $dependentOn, $bindings);
	}

	public static function operatorAwareWhere(
		string $colKey,
		string $operator,
		?array $dependentOn = null,
		mixed $bindings = null,
		mixed $value = null,
	): self {
		$bindings ??= $value;

		return new self(
			colKey: $colKey,
			type: SqlLogicalOperatorType::RAW,
			dependentOn: $dependentOn,
			bindings: $bindings,
			sql: function (mixed $bindings) use ($colKey, $operator) {
				if (is_array($bindings)) {
					return [
						'sql' => "{$colKey} IN ?",
						'bindings' => $bindings,
					];
				}

				return match ($operator) {
					'contains' => [
						'sql' => "{$colKey} LIKE ?",
						'bindings' => "%{$bindings}%",
					],
					'not_contains' => [
						'sql' => "{$colKey} NOT LIKE ?",
						'bindings' => "%{$bindings}%",
					],
					'!=', 'not_equals' => [
						'sql' => "{$colKey} != ?",
						'bindings' => $bindings,
					],
					default => [
						'sql' => "{$colKey} = ?",
						'bindings' => $bindings,
					],
				};
			},
		);
	}

	public static function __callStatic(string $name, array $arguments): self
	{
		if (isset($arguments['type']) && $arguments['type']->name === SqlLogicalOperatorType::RAW->name) {
			return (new self(
				colKey: $arguments['colKey'],
				type: $arguments['type'],
				dependentOn: $arguments['dependentOn'] ?? null,
				bindings: $arguments['bindings'] ?? $arguments['value'] ?? null,
				sql: isset($arguments['sql']) ? Closure::fromCallable($arguments['sql']) : null,
			))->asRaw(...$arguments);
		}

		return new self(
			colKey: $arguments['colKey'],
			type: SqlLogicalOperatorType::fromMethodName($name),
			dependentOn: $arguments['dependentOn'] ?? null,
			bindings: $arguments['bindings'] ?? $arguments['value'] ?? null,
		);
	}
}
