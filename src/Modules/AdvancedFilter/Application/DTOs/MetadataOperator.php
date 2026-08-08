<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\DTOs;

final readonly class MetadataOperator
{
	public function __construct(
		public string $value,
		public string $label,
		public string $valueComponent,
		public ?string $placeholder = null,
		public ?string $valueSource = null,
	) {}

	public function toArray(): array
	{
		return [
			'value' => $this->value,
			'label' => $this->label,
			'valueComponent' => $this->valueComponent,
			'placeholder' => $this->placeholder,
			'valueSource' => $this->valueSource,
		];
	}
}