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
		public ?string $valueComponent = null,
		public ?string $valueSource = null,
		public array $operators = [],
	) {}

	public function toArray(): array
	{
		$data = [
			'value' => $this->value,
			'label' => $this->label,
			'dataType' => $this->dataType,
		];

		if ($this->defaultOperator !== null) {
			$data['defaultOperator'] = $this->defaultOperator;
		}

		if ($this->valueComponent !== null) {
			$data['valueComponent'] = $this->valueComponent;
		}

		if ($this->valueSource !== null) {
			$data['valueSource'] = $this->valueSource;
		}

		if (!empty($this->operators)) {
			$data['operators'] = array_map(
				fn (MetadataOperator $operator) => $operator->toArray(),
				$this->operators
			);
		}

		return $data;
	}
}
