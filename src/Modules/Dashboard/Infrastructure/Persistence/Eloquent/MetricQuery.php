<?php

namespace BilliftySDK\SharedResources\Modules\Dashboard\Infrastructure\Persistence\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

interface MetricQuery
{
	public function for(Request $request): array;

	public function query(Request $request): Builder;
}