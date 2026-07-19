<?php

namespace BilliftySDK\SharedResources\Modules\Dashboard\Application\UseCases;

use BilliftySDK\SharedResources\Modules\Dashboard\Infrastructure\Persistence\Eloquent\DashboardMetricsQuery;
use Illuminate\Http\Request;

final class GenerateDashboard
{
	public function __construct(
        private DashboardMetricsQuery $metricsQuery,
    ) {
	}

    public function handle(Request $request): array
    {
        return $this->metricsQuery->for($request);
    }
}
