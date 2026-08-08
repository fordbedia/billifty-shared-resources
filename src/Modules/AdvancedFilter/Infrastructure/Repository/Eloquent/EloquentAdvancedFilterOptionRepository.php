<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Repository\Eloquent;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\Repository\Ports\AdvancedFilterOptionRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\BusinessProfiles;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Clients;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Workspace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EloquentAdvancedFilterOptionRepository implements AdvancedFilterOptionRepository
{
	public function defaultWorkspaceIdForUser(int $userId): ?int
	{
		return Workspace::ensureDefaultForUser($userId)->getKey();
	}

	public function invoiceNumbers(int $workspaceId, string $search, int $page, int $perPage): Collection
	{
		$query = Invoices::query()
			->where('workspace_id', $workspaceId)
			->whereNotNull('invoice_number')
			->orderByDesc('created_at')
			->orderByDesc('id');

		if ($search !== '') {
			$query->where('invoice_number', 'like', $this->likeSearch($search));
		}

		return $query
			->offset($this->offset($page, $perPage))
			->limit($perPage + 1)
			->pluck('invoice_number')
			->values();
	}

	public function businessProfiles(int $workspaceId, string $search, int $page, int $perPage): Collection
	{
		$query = BusinessProfiles::query()
			->where('workspace_id', $workspaceId)
			->orderByDesc('created_at')
			->orderByDesc('id');

		if ($search !== '') {
			$query->where(function (Builder $searchQuery) use ($search): void {
				$like = $this->likeSearch($search);

				$searchQuery
					->where('name', 'like', $like)
					->orWhere('legal_name', 'like', $like)
					->orWhere('email', 'like', $like);
			});
		}

		return $query
			->offset($this->offset($page, $perPage))
			->limit($perPage + 1)
			->get(['id', 'name', 'legal_name', 'email']);
	}

	public function clients(int $workspaceId, string $search, int $page, int $perPage): Collection
	{
		$query = Clients::query()
			->where('workspace_id', $workspaceId)
			->orderByDesc('created_at')
			->orderByDesc('id');

		if ($search !== '') {
			$query->where(function (Builder $searchQuery) use ($search): void {
				$like = $this->likeSearch($search);

				$searchQuery
					->where('name', 'like', $like)
					->orWhere('email', 'like', $like);
			});
		}

		return $query
			->offset($this->offset($page, $perPage))
			->limit($perPage + 1)
			->get(['id', 'name', 'email']);
	}

	private function offset(int $page, int $perPage): int
	{
		return ($page - 1) * $perPage;
	}

	private function likeSearch(string $search): string
	{
		return '%' . addcslashes($search, '\\%_') . '%';
	}
}
