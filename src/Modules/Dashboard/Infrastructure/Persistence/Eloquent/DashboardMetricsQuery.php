<?php

namespace BilliftySDK\SharedResources\Modules\Dashboard\Infrastructure\Persistence\Eloquent;

use Illuminate\Http\Request;

final class DashboardMetricsQuery
{
	public function __construct(
		private InvoiceMetricsQuery $invoiceMetrics,
		private InvoiceRevenueChartQuery $invoiceRevenueChart,
	) {
	}

	public function for(Request $request): array
	{
		return [
			'invoices' => $this->invoiceMetrics->for($request),
			'revenue_overview' => $this->invoiceRevenueChart->for($request),
		];
	}
}
