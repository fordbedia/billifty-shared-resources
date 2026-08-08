<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\Services;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\DTOs\MetadataField;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\DTOs\MetadataOperator;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\DTOs\MetadataSubField;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Repository\Eloquent\EloquentAdvancedFilterFieldRepository;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Models\AdvancedFilterField;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Models\AdvancedFilterFieldOperator;

class Metadata
{
	private const TYPE_ENUM = 'enum';
	private const VALUE_SINGLE_SELECT = 'single_select';

	public function __construct(protected string $module = 'invoices')
	{}

	public function getMetadata(): array
	{
		$aff = app(EloquentAdvancedFilterFieldRepository::class);

		$fields = $aff->module($this->module);

		return $fields
			->map(fn(AdvancedFilterField $field) => $this->toFieldDTO($field)->toArray())
			->values()
			->all();
	}

	private function toFieldDTO(AdvancedFilterField $field): MetadataField
	{
		if ($field->has_sub_fields) {
			return new MetadataField(
				value: $field->field_key,
				label: $field->label,
				defaultSubField: $field->default_sub_field_key,
				subFields: $field->children
					->map(fn(AdvancedFilterField $child) => $this->toSubFieldDTO($child))
					->values()
					->all(),
			);
		}

		return new MetadataField(
			value: $field->field_key,
			label: $field->label,
			dataType: $field->data_type,
			defaultOperator: $field->default_operator_key,
			valueComponent: $this->valueComponentFor($field),
			valueSource: $field->value_source,
			operators: $field->fieldOperators
				->map(fn(AdvancedFilterFieldOperator $fieldOperator) => $this->toOperatorDTO($fieldOperator))
				->values()
				->all(),
		);
	}

	private function toSubFieldDTO(AdvancedFilterField $field): MetadataSubField
	{
		return new MetadataSubField(
			value: $field->field_key,
			label: $field->label,
			dataType: $field->data_type,
			defaultOperator: $field->default_operator_key,
			valueComponent: $this->valueComponentFor($field),
			valueSource: $field->value_source,
			operators: $field->fieldOperators
				->map(fn(AdvancedFilterFieldOperator $fieldOperator) => $this->toOperatorDTO($fieldOperator))
				->values()
				->all(),
		);
	}

	private function toOperatorDTO(AdvancedFilterFieldOperator $fieldOperator): MetadataOperator
	{
		$operator = $fieldOperator->operator;

		return new MetadataOperator(
			value: $operator->operator_key,
			label: $operator->label,
			valueComponent: $operator->value_component,
			placeholder: $fieldOperator->placeholder ?? $operator->placeholder,
			valueSource: $fieldOperator->value_source ?? $operator->value_source,
		);
	}

	private function valueComponentFor(AdvancedFilterField $field): ?string
	{
		if ($field->fieldOperators->isNotEmpty()) {
			return null;
		}

		return match ($field->data_type) {
			self::TYPE_ENUM => self::VALUE_SINGLE_SELECT,
			default => null,
		};
	}
}
