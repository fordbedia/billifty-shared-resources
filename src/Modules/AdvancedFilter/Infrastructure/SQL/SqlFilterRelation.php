<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL;

class SqlFilterRelation
{
	public function __construct(
		public string $metaKey,
		public string $joinKey,
		public array $fields = []
	){}

	public static function make(string $metaKey, string $joinKey, array $fields = []): self
	{
		return new self($metaKey, $joinKey, $fields);
	}

	public function metaKey(): string
	{
		return $this->metaKey;
	}

	public function field(string $subField): ?SqlFilterFieldDefinition
	{
		return $this->fields[$subField] ?? null;
	}
}