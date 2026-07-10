<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Database\Seeders;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Models\AdvancedFilterField;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Models\AdvancedFilterFieldOperator;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Models\AdvancedFilterOperator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use BilliftySDK\SharedResources\SDK\Database\MakeSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdvancedFilterSeeder extends MakeSeeder
{
	private const MODULE_INVOICES = 'invoices';

	private const GROUP_INVOICE = 'Invoice';
	private const GROUP_BUSINESS_PROFILE = 'Business Profile';
	private const GROUP_CLIENT = 'Client';

	private const TYPE_STRING = 'string';
	private const TYPE_DATE = 'date';
	private const TYPE_ENUM = 'enum';

	private const OP_CONTAINS = 'contains';
	private const OP_NOT_CONTAINS = 'not_contains';
	private const OP_LIKE = 'like';
	private const OP_EQUALS = '=';
	private const OP_NOT_EQUALS = '!=';
	private const OP_DATE_RANGE = 'date_range';

	private const VALUE_TEXT = 'text';
	private const VALUE_MULTI_SELECT = 'multi_select';
	private const VALUE_DATE_RANGE = 'date_range';

	private const SOURCE_INVOICE_NUMBERS = 'invoice_numbers';
	private const SOURCE_STATUSES = 'statuses';

	public function run(): void
	{
		$this->revert();

		DB::transaction(function () {
			$this->seedOperators();
			$this->seedInvoiceModuleFields();
		});
	}

	private function seedOperators(): void
	{
		foreach ($this->operators() as $operator) {
			DB::table('advanced_filter_operators')->updateOrInsert(
				[
					'operator_key' => $operator['operator_key'],
				],
				$this->withTimestamps($operator)
			);
		}
	}

	private function seedInvoiceModuleFields(): void
	{
		$this->seedTopLevelInvoiceFields();
		$this->seedBusinessProfileFields();
		$this->seedClientFields();
	}

	private function seedTopLevelInvoiceFields(): void
	{
		$fields = [
			[
				'field_key' => 'invoice_number',
				'label' => 'Invoice Number',
				'data_type' => self::TYPE_STRING,
				'default_operator_key' => self::OP_CONTAINS,
				'sort_order' => 10,
				'operators' => [
					$this->multiSelectOperator(
						operatorKey: self::OP_CONTAINS,
						placeholder: 'Choose invoice numbers',
						valueSource: self::SOURCE_INVOICE_NUMBERS,
						isDefault: true,
						sortOrder: 10
					),
					$this->textOperator(self::OP_EQUALS, 'Enter invoice number', false, 20),
					$this->textOperator(self::OP_LIKE, 'Enter invoice number', false, 30),
					$this->textOperator(self::OP_NOT_EQUALS, 'Enter invoice number', false, 40),
				],
			],
			[
				'field_key' => 'issued_at',
				'label' => 'Issued On',
				'data_type' => self::TYPE_DATE,
				'default_operator_key' => self::OP_DATE_RANGE,
				'sort_order' => 20,
				'operators' => [
					$this->dateRangeOperator(isDefault: true),
				],
			],
			[
				'field_key' => 'created_at',
				'label' => 'Created On',
				'data_type' => self::TYPE_DATE,
				'default_operator_key' => self::OP_DATE_RANGE,
				'sort_order' => 30,
				'operators' => [
					$this->dateRangeOperator(isDefault: true),
				],
			],
			[
				'field_key' => 'status',
				'label' => 'Status',
				'data_type' => self::TYPE_ENUM,
				'default_operator_key' => self::OP_CONTAINS,
				'sort_order' => 40,
				'operators' => [
					$this->multiSelectOperator(
						operatorKey: self::OP_CONTAINS,
						placeholder: 'Choose statuses',
						valueSource: self::SOURCE_STATUSES,
						isDefault: true,
						sortOrder: 10
					),
					$this->multiSelectOperator(
						operatorKey: self::OP_NOT_CONTAINS,
						placeholder: 'Choose statuses',
						valueSource: self::SOURCE_STATUSES,
						isDefault: false,
						sortOrder: 20
					),
				],
			],
		];

		foreach ($fields as $field) {
			$fieldId = $this->upsertField([
				'module' => self::MODULE_INVOICES,
				'parent_id' => null,
				'field_key' => $field['field_key'],
				'label' => $field['label'],
				'group_label' => self::GROUP_INVOICE,
				'data_type' => $field['data_type'],
				'has_sub_fields' => false,
				'default_sub_field_key' => null,
				'default_operator_key' => $field['default_operator_key'],
				'value_source' => null,
				'is_enabled' => true,
				'sort_order' => $field['sort_order'],
			]);

			$this->attachOperators($fieldId, $field['operators']);
		}
	}

	private function seedBusinessProfileFields(): void
	{
		$parentId = $this->seedParentField([
			'field_key' => 'business_profile',
			'label' => 'Business Profile',
			'group_label' => self::GROUP_BUSINESS_PROFILE,
			'default_sub_field_key' => 'name',
			'sort_order' => 50,
		]);

		$this->seedSubFields(
			parentId: $parentId,
			groupLabel: self::GROUP_BUSINESS_PROFILE,
			fields: [
				$this->textSubField('name', 'Name', 'Enter business profile name', 10),
				$this->textSubField('legal_name', 'Legal Name', 'Enter legal name', 20),
				$this->textSubField('email', 'Email Address', 'Enter business profile email', 30),
				$this->textEqualitySubField('phone', 'Phone', 'Enter business profile phone', 40),
				$this->textEqualitySubField('website', 'Website', 'Enter website', 50),
				$this->dateSubField('created_at', 'Date Created', 60),
				$this->textSubField('ein', 'EIN', 'Enter EIN', 70),
			]
		);
	}

	private function seedClientFields(): void
	{
		$parentId = $this->seedParentField([
			'field_key' => 'client',
			'label' => 'Client',
			'group_label' => self::GROUP_CLIENT,
			'default_sub_field_key' => 'name',
			'sort_order' => 60,
		]);

		$this->seedSubFields(
			parentId: $parentId,
			groupLabel: self::GROUP_CLIENT,
			fields: [
				$this->textSubField('name', 'Name', 'Enter client name', 10),
				$this->textSubField('email', 'Email Address', 'Enter client email', 20),
				$this->textSubField('phone', 'Phone', 'Enter client phone', 30),
				$this->textSubField('billing_address', 'Billing Address', 'Enter billing address', 40),
				$this->textSubField('shipping_address', 'Shipping Address', 'Enter shipping address', 50),
				$this->dateSubField('created_at', 'Date Created', 60),
			]
		);
	}

	private function seedParentField(array $field): int
	{
		return $this->upsertField([
			'module' => self::MODULE_INVOICES,
			'parent_id' => null,
			'field_key' => $field['field_key'],
			'label' => $field['label'],
			'group_label' => $field['group_label'],
			'data_type' => null,
			'has_sub_fields' => true,
			'default_sub_field_key' => $field['default_sub_field_key'],
			'default_operator_key' => null,
			'value_source' => null,
			'is_enabled' => true,
			'sort_order' => $field['sort_order'],
		]);
	}

	private function seedSubFields(int $parentId, string $groupLabel, array $fields): void
	{
		foreach ($fields as $field) {
			$fieldId = $this->upsertField([
				'module' => self::MODULE_INVOICES,
				'parent_id' => $parentId,
				'field_key' => $field['field_key'],
				'label' => $field['label'],
				'group_label' => $groupLabel,
				'data_type' => $field['data_type'],
				'has_sub_fields' => false,
				'default_sub_field_key' => null,
				'default_operator_key' => $field['default_operator_key'],
				'value_source' => null,
				'is_enabled' => true,
				'sort_order' => $field['sort_order'],
			]);

			$this->attachOperators($fieldId, $field['operators']);
		}
	}

	private function textSubField(
		string $fieldKey,
		string $label,
		string $placeholder,
		int    $sortOrder
	): array
	{
		return [
			'field_key' => $fieldKey,
			'label' => $label,
			'data_type' => self::TYPE_STRING,
			'default_operator_key' => self::OP_LIKE,
			'sort_order' => $sortOrder,
			'operators' => $this->textComparisonOperators($placeholder),
		];
	}

	private function textEqualitySubField(
		string $fieldKey,
		string $label,
		string $placeholder,
		int    $sortOrder
	): array
	{
		return [
			'field_key' => $fieldKey,
			'label' => $label,
			'data_type' => self::TYPE_STRING,
			'default_operator_key' => self::OP_EQUALS,
			'sort_order' => $sortOrder,
			'operators' => $this->textEqualityOperators($placeholder),
		];
	}

	private function dateSubField(
		string $fieldKey,
		string $label,
		int    $sortOrder
	): array
	{
		return [
			'field_key' => $fieldKey,
			'label' => $label,
			'data_type' => self::TYPE_DATE,
			'default_operator_key' => self::OP_DATE_RANGE,
			'sort_order' => $sortOrder,
			'operators' => [
				$this->dateRangeOperator(isDefault: true),
			],
		];
	}

	private function textComparisonOperators(string $placeholder): array
	{
		return [
			$this->textOperator(self::OP_LIKE, $placeholder, true, 10),
			$this->textOperator(self::OP_EQUALS, $placeholder, false, 20),
			$this->textOperator(self::OP_NOT_EQUALS, $placeholder, false, 30),
		];
	}

	private function textEqualityOperators(string $placeholder): array
	{
		return [
			$this->textOperator(self::OP_EQUALS, $placeholder, true, 10),
			$this->textOperator(self::OP_NOT_EQUALS, $placeholder, false, 20),
		];
	}

	private function operators(): array
	{
		return [
			[
				'operator_key' => self::OP_CONTAINS,
				'label' => 'Contains',
				'value_component' => self::VALUE_MULTI_SELECT,
				'placeholder' => null,
				'value_source' => null,
				'requires_value' => true,
				'is_enabled' => true,
				'sort_order' => 10,
			],
			[
				'operator_key' => self::OP_NOT_CONTAINS,
				'label' => 'Not Contains',
				'value_component' => self::VALUE_MULTI_SELECT,
				'placeholder' => null,
				'value_source' => null,
				'requires_value' => true,
				'is_enabled' => true,
				'sort_order' => 20,
			],
			[
				'operator_key' => self::OP_EQUALS,
				'label' => 'Equals (=)',
				'value_component' => self::VALUE_TEXT,
				'placeholder' => null,
				'value_source' => null,
				'requires_value' => true,
				'is_enabled' => true,
				'sort_order' => 30,
			],
			[
				'operator_key' => self::OP_LIKE,
				'label' => 'Includes text',
				'value_component' => self::VALUE_TEXT,
				'placeholder' => null,
				'value_source' => null,
				'requires_value' => true,
				'is_enabled' => true,
				'sort_order' => 35,
			],
			[
				'operator_key' => self::OP_NOT_EQUALS,
				'label' => 'Not equals (!=)',
				'value_component' => self::VALUE_TEXT,
				'placeholder' => null,
				'value_source' => null,
				'requires_value' => true,
				'is_enabled' => true,
				'sort_order' => 40,
			],
			[
				'operator_key' => self::OP_DATE_RANGE,
				'label' => 'Date Range',
				'value_component' => self::VALUE_DATE_RANGE,
				'placeholder' => null,
				'value_source' => null,
				'requires_value' => true,
				'is_enabled' => true,
				'sort_order' => 50,
			],
		];
	}

	private function textOperator(
		string $operatorKey,
		string $placeholder,
		bool   $isDefault,
		int    $sortOrder
	): array
	{
		return [
			'operator_key' => $operatorKey,
			'is_default' => $isDefault,
			'placeholder' => $placeholder,
			'value_source' => null,
			'sort_order' => $sortOrder,
		];
	}

	private function multiSelectOperator(
		string $operatorKey,
		string $placeholder,
		string $valueSource,
		bool   $isDefault,
		int    $sortOrder
	): array
	{
		return [
			'operator_key' => $operatorKey,
			'is_default' => $isDefault,
			'placeholder' => $placeholder,
			'value_source' => $valueSource,
			'sort_order' => $sortOrder,
		];
	}

	private function dateRangeOperator(bool $isDefault): array
	{
		return [
			'operator_key' => self::OP_DATE_RANGE,
			'is_default' => $isDefault,
			'placeholder' => null,
			'value_source' => null,
			'sort_order' => 10,
		];
	}

	private function upsertField(array $field): int
	{
		DB::table('advanced_filter_fields')->updateOrInsert(
			[
				'module' => $field['module'],
				'parent_id' => $field['parent_id'],
				'field_key' => $field['field_key'],
			],
			$this->withTimestamps($field)
		);

		return (int)DB::table('advanced_filter_fields')
			->where('module', $field['module'])
			->where('field_key', $field['field_key'])
			->when(
				is_null($field['parent_id']),
				fn($query) => $query->whereNull('parent_id'),
				fn($query) => $query->where('parent_id', $field['parent_id'])
			)
			->value('id');
	}

	private function attachOperators(int $fieldId, array $operators): void
	{
		foreach ($operators as $operator) {
			$operatorId = DB::table('advanced_filter_operators')
				->where('operator_key', $operator['operator_key'])
				->value('id');

			if (!$operatorId) {
				continue;
			}

			DB::table('advanced_filter_field_operators')->updateOrInsert(
				[
					'advanced_filter_field_id' => $fieldId,
					'advanced_filter_operator_id' => $operatorId,
				],
				$this->withTimestamps([
					'is_default' => $operator['is_default'] ?? false,
					'placeholder' => $operator['placeholder'] ?? null,
					'value_source' => $operator['value_source'] ?? null,
					'sort_order' => $operator['sort_order'] ?? 0,
				])
			);
		}
	}

	private function withTimestamps(array $data): array
	{
		return array_merge($data, [
			'created_at' => now(),
			'updated_at' => now(),
		]);
	}

	public function revert()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		AdvancedFilterFieldOperator::truncate();
		AdvancedFilterField::truncate();
		AdvancedFilterOperator::truncate();

		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}
}
