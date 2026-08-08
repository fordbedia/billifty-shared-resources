<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\Repository\Ports;

use Illuminate\Support\Collection;

interface AdvancedFilterOptionRepository
{
	public function defaultWorkspaceIdForUser(int $userId): ?int;

	public function invoiceNumbers(int $workspaceId, string $search, int $page, int $perPage): Collection;

	public function businessProfiles(int $workspaceId, string $search, int $page, int $perPage): Collection;

	public function clients(int $workspaceId, string $search, int $page, int $perPage): Collection;
}
