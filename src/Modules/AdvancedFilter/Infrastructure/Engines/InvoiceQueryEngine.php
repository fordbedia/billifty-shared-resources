<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Engines;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Enums\SqlLogicalOperatorType;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL\RawSqlQuery;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL\SqlFilterRelation;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL\SqlLogicalFieldOperator;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\DTOs\AdvancedFilterInput;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Engines\QueryEngine;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use function Illuminate\Events\queueable;

class InvoiceQueryEngine implements QueryEngine
{
	const MODULE_TABLE = 'invoices';

	public function __construct(
		private readonly AdvancedFilterQueryProcessor $processor,
	)
	{
	}

	public function joins(): array
	{
		return [
			'bp' => [
				'sql' => 'inner join business_profiles bp on bp.id = i.business_profile_id',
				'bindings' => []
			],
			'c' => [
				'sql' => 'inner join clients c on c.id = i.client_id',
				'bindings' => []
			],
		];
	}

	public function baseSql(): RawSqlQuery
	{
		return new RawSqlQuery(
			sql: 'select i.* from ' . self::MODULE_TABLE . ' i where i.user_id = ?',
			bindings: [auth()->id()],
		);
	}

	public function fields($input): array
	{
		$operator = $this->operatorFromInput($input);

		return [
			'invoice_number' => SqlLogicalFieldOperator::where(
				colKey: 'i.invoice_number',
				type: SqlLogicalOperatorType::RAW,
				bindings: $input['value'],
				dependentOn: ['i'],
				sql: function ($bindings) use ($input) {
					if (is_array($bindings)) {
						if ($input['operator'] === 'contains') {
							return [
								'sql' => 'i.invoice_number IN ?',
								'bindings' => [$bindings],
							];
						} else {
							return [
								'sql' => 'i.invoice_number NOT IN ?',
								'bindings' => [$bindings],
							];
						}
					}

					if (is_string($bindings)) {
						if ($input['operator'] === '=') {
							return [
								'sql' => 'i.invoice_number = ?',
								'bindings' => [$bindings],
							];
						} else if ($input['operator'] === '!=') {
							return [
								'sql' => 'i.invoice_number != ?',
								'bindings' => [$bindings],
							];
						}
					}

					return [
						'sql' => 'i.invoice_number = ?',
						'bindings' => ["%{$bindings}%"],
					];
				}
			),
			'status' => SqlLogicalFieldOperator::whereIn(
				colKey: 'i.status',
				bindings: $input['value'],
				dependentOn: ['i'],
			),
			'issued_at' => SqlLogicalFieldOperator::whereBetween(
				colKey: 'i.issued_at',
				bindings: $input['value'],
				dependentOn: ['i'],
			),
			'created_at' => SqlLogicalFieldOperator::whereBetween(
				colKey: 'i.created_at',
				bindings: $input['value'],
				dependentOn: ['i'],
			),
			'client' => SqlFilterRelation::make(
				metaKey: 'client',
				joinKey: 'c',
				fields: [
						'name' => SqlLogicalFieldOperator::operatorAwareWhere(
							colKey: 'c.name',
							operator: $operator,
							dependentOn: ['c'],
							bindings: $input['value'],
						),
						'email' => SqlLogicalFieldOperator::operatorAwareWhere(
							colKey: 'c.email',
							operator: $operator,
							dependentOn: ['c'],
							bindings: $input['value'],
						),
						'phone' => SqlLogicalFieldOperator::operatorAwareWhere(
							colKey: 'c.phone',
							operator: $operator,
							dependentOn: ['c'],
							bindings: $input['value'],
						),
						'billing_address' => SqlLogicalFieldOperator::operatorAwareWhere(
							colKey: 'c.billing_address',
							operator: $operator,
							dependentOn: ['c'],
							bindings: $input['value'],
						),
						'shipping_address' => SqlLogicalFieldOperator::operatorAwareWhere(
							colKey: 'c.shipping_address',
							operator: $operator,
							dependentOn: ['c'],
							bindings: $input['value'],
						),
						'created_at' => SqlLogicalFieldOperator::whereBetween(
							colKey: 'c.created_at',
							dependentOn: ['c'],
							bindings: $input['value'],
						),
						'invoice' => SqlLogicalFieldOperator::where(
							colKey: 'i.status',
							type: SqlLogicalOperatorType::RAW,
							dependentOn: ['i'],
							bindings: $input['value'],
							sql: function ($value) {
								if ($value === 'created') {
									// Check using exist
									return [
										'sql' => 'exists (select 1 from ? i2 where i2.client_id = c.id)',
										'bindings' => [self::MODULE_TABLE],
									];
								}
								return [
									'sql' => 'i.status = ?',
									'bindings' => [$value],
								];
							}
						)
				]
			),
			'business_profile' => SqlFilterRelation::make(
				metaKey: 'business_profile',
				joinKey: 'bp',
				fields: [
						'name' => SqlLogicalFieldOperator::operatorAwareWhere(
							colKey: 'bp.name',
							operator: $operator,
							dependentOn: ['bp'],
							bindings: $input['value'],
						),
						'legal_name' => SqlLogicalFieldOperator::operatorAwareWhere(
							colKey: 'bp.legal_name',
							operator: $operator,
							dependentOn: ['bp'],
							bindings: $input['value'],
						),
						'email' => SqlLogicalFieldOperator::operatorAwareWhere(
							colKey: 'bp.email',
							operator: $operator,
							dependentOn: ['bp'],
							bindings: $input['value'],
						),
						'phone' => SqlLogicalFieldOperator::operatorAwareWhere(
							colKey: 'bp.phone',
							operator: $operator,
							dependentOn: ['bp'],
							bindings: $input['value'],
						),
						'website' => SqlLogicalFieldOperator::operatorAwareWhere(
							colKey: 'bp.website',
							operator: $operator,
							dependentOn: ['bp'],
							bindings: $input['value'],
						),
						'ein' => SqlLogicalFieldOperator::operatorAwareWhere(
							colKey: 'bp.ein',
							operator: $operator,
							dependentOn: ['bp'],
							bindings: $input['value'],
						),
						'created_at' => SqlLogicalFieldOperator::whereBetween(
							colKey: 'bp.created_at',
							dependentOn: ['bp'],
							bindings: $input['value'],
						),
				]
			)
		];
	}

	private function operatorFromInput(array $input): string
	{
		$operator = $input['operator'] ?? null;

		return is_string($operator) && $operator !== '' ? $operator : '=';
	}

	public function search(AdvancedFilterInput $input, int $perPage = 15): LengthAwarePaginator
	{
		$query = $this->processor->build($this, $input);

		$page = 1;

		return new Paginator(
			items: collect([]),
			total: (int)0,
			perPage: $perPage,
			currentPage: $page,
		);
	}
}
