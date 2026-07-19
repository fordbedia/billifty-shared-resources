<?php

namespace BilliftySDK\SharedResources\Modules\Dashboard\Infrastructure\Persistence\Eloquent;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class InvoiceMetricsQuery
{
	public function for(Request $request): array
	{
		$currentMetrics = $this->metrics($this->query($request));
		$comparisonPeriod = $this->comparisonPeriod($request);

		if ($comparisonPeriod === null) {
			return [
				...$currentMetrics,
				'total_revenue_change' => null,
				'outstanding_change' => null,
				'overdue_change' => null,
				'draft_invoices_change' => null,
				'comparison_period' => null,
			];
		}

		$previousMetrics = $this->metrics(
			$this->baseQuery($request)->whereBetween('issued_on', [
				$comparisonPeriod['start_date'],
				$comparisonPeriod['end_date'],
			])
		);
		$comparisonLabel = $this->comparisonLabel($request);

		return [
			...$currentMetrics,
			'total_revenue_change' => $this->change(
				$currentMetrics['total_revenue_cents'],
				$previousMetrics['total_revenue_cents'],
				$comparisonLabel
			),
			'outstanding_change' => $this->change(
				$currentMetrics['outstanding_cents'],
				$previousMetrics['outstanding_cents'],
				$comparisonLabel
			),
			'overdue_change' => $this->change(
				$currentMetrics['overdue_cents'],
				$previousMetrics['overdue_cents'],
				$comparisonLabel
			),
			'draft_invoices_change' => $this->change(
				$currentMetrics['draft_invoices_count'],
				$previousMetrics['draft_invoices_count'],
				$comparisonLabel
			),
			'comparison_period' => $comparisonPeriod,
		];
	}

	private function query(Request $request): Builder
	{
		$query = $this->baseQuery($request);

		if ($request->filled(['start_date', 'end_date'])) {
			$query->whereBetween('issued_on', [
				$request->string('start_date')->toString(),
				$request->string('end_date')->toString(),
			]);
		}

		return $query;
	}

	private function baseQuery(Request $request): Builder
	{
		$userId = $request->user()?->getAuthIdentifier();

		return Invoices::query()
			->when(
				$userId,
				fn (Builder $invoiceQuery) => $invoiceQuery->whereHas(
					'workspace',
					fn (Builder $workspaceQuery) => $workspaceQuery->where('user_id', $userId)
				),
				fn (Builder $invoiceQuery) => $invoiceQuery->whereRaw('1 = 0')
			);
	}

	private function metrics(Builder $query): array
	{
		return [
			'total_revenue_cents' => (int) (clone $query)
				->where('status', 'paid')
				->sum('total_cents'),
			'outstanding_cents' => (int) (clone $query)
				->whereIn('status', ['issued', 'sent', 'partially'])
				->where('amount_due_cents', '>', 0)
				->sum('amount_due_cents'),
			'overdue_cents' => (int) (clone $query)
				->whereIn('status', ['issued', 'sent', 'partially'])
				->whereDate('due_on', '<', now()->toDateString())
				->where('amount_due_cents', '>', 0)
				->sum('amount_due_cents'),
			'draft_invoices_count' => (int) (clone $query)
				->where('status', 'draft')
				->count(),
		];
	}

	private function comparisonPeriod(Request $request): ?array
	{
		if (! $request->filled(['start_date', 'end_date'])) {
			return null;
		}

		$currentStart = CarbonImmutable::parse($request->string('start_date')->toString());
		$currentEnd = CarbonImmutable::parse($request->string('end_date')->toString());
		$periodDays = (int) $currentStart->diffInDays($currentEnd) + 1;
		$previousEnd = $currentStart->subDay();
		$previousStart = $previousEnd->subDays($periodDays - 1);

		return [
			'start_date' => $previousStart->toDateString(),
			'end_date' => $previousEnd->toDateString(),
		];
	}

	private function comparisonLabel(Request $request): string
	{
		return match ($request->string('period')->toString()) {
			'last-month' => 'last month',
			'last-3-months' => 'last 3 months',
			'last-6-months' => 'last 6 months',
			'last-year' => 'last year',
			default => 'previous period',
		};
	}

	private function change(int $current, int $previous, string $comparisonLabel): array
	{
		if ($current === $previous) {
			return [
				'percentage' => 0.0,
				'direction' => 'flat',
				'previous_value' => $previous,
				'label' => "No change vs {$comparisonLabel}",
			];
		}

		$direction = $current > $previous ? 'up' : 'down';

		if ($previous === 0) {
			return [
				'percentage' => null,
				'direction' => $direction,
				'previous_value' => $previous,
				'label' => "New vs {$comparisonLabel}",
			];
		}

		$percentage = round(abs(($current - $previous) / $previous) * 100, 1);

		return [
			'percentage' => $percentage,
			'direction' => $direction,
			'previous_value' => $previous,
			'label' => number_format($percentage, 1) . "% vs {$comparisonLabel}",
		];
	}
}
