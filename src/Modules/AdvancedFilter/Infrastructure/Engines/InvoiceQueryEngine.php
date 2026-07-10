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
	public function __construct(
		private readonly AdvancedFilterQueryProcessor $processor,
	) {}

	public function joins(): array
	{
		return [
			'bp' => [
				'sql' => 'inner join business_profiles bp on bp.id = i.business_profile_id',
				'bindings' => []
			],
			'c' => [
				'sql' => 'left join clients c on c.id = i.client_id',
				'bindings' => []
			],
		];
	}

	public function baseSql(): RawSqlQuery
	{
		return new RawSqlQuery(
			sql: "select i.* from invoices i where i.user_id = ?",
			bindings: [auth()->id()],
		);
	}

	public function fields($input): array
	{
		return [
			'invoice_number' => SqlLogicalFieldOperator::where(
				colKey: 'i.invoice_number',
				type: SqlLogicalOperatorType::RAW,
				bindings: $input['value'],
				sql: function($bindings) {
					if (is_array($bindings)) {
						return [
							'sql' => 'i.invoice_number IN ?',
							'bindings' => $bindings,
						];
					}

					return [
						'sql' => 'i.invoice_number = ?',
						'bindings' => $bindings,
					];
				}
			),
			'status' => SqlLogicalFieldOperator::whereIn(
				colKey: 'i.status',
				bindings: $input['value'],
			),
			'issued_at' => SqlLogicalFieldOperator::whereBetween(
				colKey: 'i.issued_at',
				bindings: $input['value'],
			),
			'created_at' => SqlLogicalFieldOperator::whereBetween(
				colKey: 'i.created_at',
				bindings: $input['value'],
			),
			'client' => SqlFilterRelation::make(
				metaKey: 'client',
				joinKey: 'c',
				fields: [
					'name' => SqlLogicalFieldOperator::operatorAwareWhere(
						colKey: 'c.name',
						operator: $input['operator'],
						dependentOn: ['c'],
						bindings: $input['value'],
					),
					'email' => SqlLogicalFieldOperator::operatorAwareWhere(
						colKey: 'c.email',
						operator: $input['operator'],
						dependentOn: ['c'],
						bindings: $input['value'],
					),
					'created_at' => SqlLogicalFieldOperator::whereBetween(
						colKey: 'c.created_at',
						dependentOn: ['c'],
						bindings: $input['value'],
					),
				]
			),
//			'business_profile' => []
		];
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
