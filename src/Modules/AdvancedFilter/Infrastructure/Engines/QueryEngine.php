<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Engines;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\DTOs\AdvancedFilterInput;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\SQL\RawSqlQuery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface QueryEngine
{
	public function baseSql(): RawSqlQuery;

	public function joins(): array;

	public function search(AdvancedFilterInput $input, int $perPage = 15): LengthAwarePaginator;
}