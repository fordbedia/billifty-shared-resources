<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\DTOs;

final readonly class MetadataSubField
{
	/**
	 * @param MetadataOperator[] $operators
	 */
	public function __construct(
		public string $value,
		public string $label,
		public ?string $dataType,
		public ?string $defaultOperator,
		public array $operators = [],
	) {}

	public function toArray(): array
	{
		return [
			'value' => $this->value,
			'label' => $this->label,
			'dataType' => $this->dataType,
			'defaultOperator' => $this->defaultOperator,
			'operators' => array_map(
				fn (MetadataOperator $operator) => $operator->toArray(),
				$this->operators
			),
		];
	}
}