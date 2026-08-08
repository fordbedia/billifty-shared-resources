<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL;

class RawSqlQuery
{
	public function __construct(
		public string $sql,
		public array  $bindings = [],
	) {}

	public static function make(string $sql, array $bindings = []): self
	{
		return new self($sql, $bindings);
	}

	public function append(string $sql, array $bindings = []): void
	{
		$this->sql .= ' ' . $sql;
		$this->bindings = array_merge($this->bindings, $bindings);
	}
}