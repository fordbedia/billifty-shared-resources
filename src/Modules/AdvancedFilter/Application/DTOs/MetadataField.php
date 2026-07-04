<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\DTOs;

final readonly class MetadataField
{
	/**
	 * @param MetadataOperator[] $operators
	 * @param MetadataSubField[] $subFields
	 */
	public function __construct(
		public string  $value,
		public string  $label,
		public ?string $dataType = null,
		public ?string $defaultOperator = null,
		public ?string $defaultSubField = null,
		public array   $operators = [],
		public array   $subFields = [],
	) {}

	public function toArray(): array
	{
		$data = [
			'value' => $this->value,
			'label' => $this->label,
		];

		if ($this->dataType !== null) {
			$data['dataType'] = $this->dataType;
		}

		if ($this->defaultOperator !== null) {
			$data['defaultOperator'] = $this->defaultOperator;
		}

		if ($this->defaultSubField !== null) {
			$data['defaultSubField'] = $this->defaultSubField;
		}

		if (!empty($this->operators)) {
			$data['operators'] = array_map(
				fn(MetadataOperator $operator) => $operator->toArray(),
				$this->operators
			);
		}

		if (!empty($this->subFields)) {
			$data['subFields'] = array_map(
				fn(MetadataSubField $subField) => $subField->toArray(),
				$this->subFields
			);
		}

		return $data;
	}
}