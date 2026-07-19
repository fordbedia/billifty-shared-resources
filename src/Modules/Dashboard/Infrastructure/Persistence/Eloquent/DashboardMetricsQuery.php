<?php

namespace BilliftySDK\SharedResources\Modules\Dashboard\Infrastructure\Persistence\Eloquent;

use Illuminate\Http\Request;

final class DashboardMetricsQuery
{
	public function __construct(
		private InvoiceMetricsQuery $invoiceMetrics,
	) {
	}

	public function for(Request $request): array
	{
		return [
			'invoices' => $this->invoiceMetrics->for($request),
		];
	}
}
