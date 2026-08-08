<?php

namespace BilliftySDK\SharedResources\Modules\Dashboard\Infrastructure\Persistence\Eloquent;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Workspace;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

final class InvoiceRevenueChartQuery implements MetricQuery
{
	public function for(Request $request): array
	{
		$isDaily = $request->string('period')->toString() === 'custom';
		$periodExpression = $isDaily
			? 'DATE(issued_on)'
			: "DATE_FORMAT(issued_on, '%Y-%m')";

		$rows = $this->query($request)
			->selectRaw("{$periodExpression} as period")
			->selectRaw("
				COALESCE(SUM(
					CASE
						WHEN status IN ('issued', 'sent', 'partially', 'paid') THEN total_cents
						ELSE 0
					END
				), 0) as revenue_cents
			")
			->selectRaw("
				COUNT(
					CASE
						WHEN status != 'void' THEN 1
						ELSE NULL
					END
				) as invoice_count
			")
			->selectRaw("
				COALESCE(SUM(
					CASE
						WHEN status = 'paid' THEN total_cents
						WHEN status = 'partially' THEN GREATEST(total_cents - amount_due_cents, 0)
						ELSE 0
					END
				), 0) as paid_cents
			")
			->selectRaw("
				COALESCE(SUM(
					CASE
						WHEN status IN ('issued', 'sent', 'partially') AND amount_due_cents > 0
							THEN amount_due_cents
						ELSE 0
					END
				), 0) as outstanding_cents
			")
			->selectRaw("
				COUNT(DISTINCT
					CASE
						WHEN status != 'void' THEN client_id
						ELSE NULL
					END
				) as client_count
			")
			->groupByRaw($periodExpression)
			->orderBy('period')
			->get()
			->keyBy('period');

		return $isDaily
			? $this->fillDailyTimeline($request, $rows)
			: $this->fillMonthlyTimeline($request, $rows);
	}

	public function query(Request $request): Builder
	{
		$userId = $request->user()?->getAuthIdentifier();
		$workspaceId = $userId
			? Workspace::query()
				->where('user_id', $userId)
				->where('is_default', 1)
				->value('id')
			: null;

		return Invoices::query()
			->whereNotNull('issued_on')
			->when(
				$workspaceId,
				fn (Builder $invoiceQuery) => $invoiceQuery->where('workspace_id', $workspaceId),
				fn (Builder $invoiceQuery) => $invoiceQuery->whereRaw('1 = 0')
			)
			->when(
				$request->filled(['start_date', 'end_date']),
				fn (Builder $invoiceQuery) => $invoiceQuery->whereBetween('issued_on', [
					$request->string('start_date')->toString(),
					$request->string('end_date')->toString(),
				])
			);
	}

	private function fillMonthlyTimeline(Request $request, Collection $rows): array
	{
		if (! $request->filled(['start_date', 'end_date'])) {
			return $rows
				->map(fn (object $row) => $this->formatMonthlyRow($row->period, $row))
				->values()
				->all();
		}

		$currentMonth = CarbonImmutable::parse(
			$request->string('start_date')->toString()
		)->startOfMonth();
		$lastMonth = CarbonImmutable::parse(
			$request->string('end_date')->toString()
		)->startOfMonth();
		$timeline = [];

		while ($currentMonth->lessThanOrEqualTo($lastMonth)) {
			$period = $currentMonth->format('Y-m');
			$timeline[] = $this->formatMonthlyRow($period, $rows->get($period));
			$currentMonth = $currentMonth->addMonth();
		}

		return $timeline;
	}

	private function fillDailyTimeline(Request $request, Collection $rows): array
	{
		if (! $request->filled(['start_date', 'end_date'])) {
			return $rows
				->map(fn (object $row) => $this->formatDailyRow($row->period, $row))
				->values()
				->all();
		}

		$currentDate = CarbonImmutable::parse($request->string('start_date')->toString());
		$lastDate = CarbonImmutable::parse($request->string('end_date')->toString());
		$timeline = [];

		while ($currentDate->lessThanOrEqualTo($lastDate)) {
			$period = $currentDate->toDateString();
			$timeline[] = $this->formatDailyRow($period, $rows->get($period));
			$currentDate = $currentDate->addDay();
		}

		return $timeline;
	}

	private function formatMonthlyRow(string $period, ?object $row): array
	{
		return $this->formatRow(
			CarbonImmutable::createFromFormat('Y-m', $period)->format("M 'y"),
			$row
		);
	}

	private function formatDailyRow(string $period, ?object $row): array
	{
		return $this->formatRow(
			CarbonImmutable::parse($period)->format("M d 'y"),
			$row
		);
	}

	private function formatRow(string $label, ?object $row): array
	{
		return [
			'month' => $label,
			'revenue_cents' => (int) ($row?->revenue_cents ?? 0),
			'invoice_count' => (int) ($row?->invoice_count ?? 0),
			'paid_cents' => (int) ($row?->paid_cents ?? 0),
			'outstanding_cents' => (int) ($row?->outstanding_cents ?? 0),
			'client_count' => (int) ($row?->client_count ?? 0),
		];
	}
}
