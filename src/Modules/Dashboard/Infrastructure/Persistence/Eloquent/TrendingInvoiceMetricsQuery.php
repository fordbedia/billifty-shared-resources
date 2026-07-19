<?php

namespace BilliftySDK\SharedResources\Modules\Dashboard\Infrastructure\Persistence\Eloquent;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Workspace;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TrendingInvoiceMetricsQuery implements MetricQuery
{
	/**
	 * @param Request $request
	 * @return array
	 */
	public function for(Request $request): array
	{
		return [
			'recent' => $this->recentInvoices($request)->get()->toArray(),
			'top_clients' => $this->topClients($request)->get()->toArray(),
		];
	}

	/**
	 * @param Request $request
	 * @return Builder
	 */
	public function query(Request $request): Builder
	{
		$userId = $request->user()?->getAuthIdentifier();
		$workspaceId = $userId
			? Workspace::query()
				->where('user_id', $userId)
				->where('is_default', 1)
				->value('id')
			: null;
		$dateRange = $this->dateRange($request);

		return Invoices::query()
			->select(['issued_on', 'invoice_number', 'total_cents', 'client_id', 'status'])
			->with(['client:id,name'])
			->when(
				$workspaceId,
				fn (Builder $builder) => $builder->where('workspace_id', $workspaceId),
				fn (Builder $builder) => $builder->whereRaw('1 = 0')
			)
			->when(
				$dateRange,
				fn (Builder $builder) => $builder->whereBetween('issued_on', [
					$dateRange['start_date'],
					$dateRange['end_date'],
				])
			);
	}

	private function dateRange(Request $request): ?array
	{
		if ($request->filled(['start_date', 'end_date'])) {
			return [
				'start_date' => $request->string('start_date')->toString(),
				'end_date' => $request->string('end_date')->toString(),
			];
		}

		$endDate = CarbonImmutable::today();
		$startDate = match ($request->string('period')->toString()) {
			'last-month' => $endDate->subMonthNoOverflow(),
			'last-3-months' => $endDate->subMonthsNoOverflow(3),
			'last-6-months' => $endDate->subMonthsNoOverflow(6),
			'last-year' => $endDate->subYearNoOverflow(),
			default => null,
		};

		if ($startDate === null) {
			return null;
		}

		return [
			'start_date' => $startDate->toDateString(),
			'end_date' => $endDate->toDateString(),
		];
	}

	private function recentInvoices(Request $request): Builder
	{
		return $this->query($request)
			->where('status', 'issued')
			->orderByDesc('issued_on')
			->limit(7);
	}

	private function topClients(Request $request): Builder
	{
		return $this->query($request)
			->select('client_id')
			->selectRaw('COUNT(*) as invoice_count')
			->selectRaw('SUM(total_cents) as total_cents')
			->whereNotNull('issued_at')
			->where('status', 'paid')
			->groupBy('client_id')
			->orderByDesc('total_cents')
			->limit(4);
	}

}
