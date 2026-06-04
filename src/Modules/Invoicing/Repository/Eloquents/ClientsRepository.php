<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Clients;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\BaseRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\ClientsContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ClientsRepository extends BaseRepository implements ClientsContract
{
	public function all()
	{
		return $this->getModelByAuthUser()->get();
	}

	public function makeModel(): string
	{
		return Clients::class;
	}

	public function findById(int $id): Model
	{
		return $this->getModelByAuthUser()->whereKey($id)->firstOrFail();
	}

	public function getModelByAuthUser(): Builder
	{
		$workspaceId = $this->defaultWorkspaceIdForAuthUser();
		$query = $this->model->newQuery();

		if (!$workspaceId) {
			return $query->whereRaw('1 = 0');
		}

		// Name retained for repository compatibility; ownership now resolves through workspace_id.
		return $query->where('workspace_id', $workspaceId);
	}

	public function save(array $data, ?int $id = null): Model
	{
		return DB::transaction(function () use ($data, $id): Model {
			$data = $this->withoutOwnershipFields($data);

			if ($id) {
				$model = $this->getModelByAuthUser()->findOrFail($id);
			} else {
				$workspaceId = $this->defaultWorkspaceIdForAuthUser();

				if (!$workspaceId) {
					throw new RuntimeException('Cannot create a client without an authenticated workspace.');
				}

				$model = new Clients();
				// Client ownership is assigned server-side to prevent spoofed workspace changes.
				$model->workspace_id = $workspaceId;
			}

			$model->fill($data);
			$model->save();

			return $model->refresh();
		});
	}

	protected function withoutOwnershipFields(array $data): array
	{
		unset($data['user_id'], $data['workspace_id']);

		return $data;
	}

	public function paginate(
        $query = null,
        int $perPage = 15,
        array $columns = ['*'],
        string $pageName = 'page',
        int|null $page = null,
		$dateRange = null,
		$search = null,
    ) {
        // Add custom condition(s)
        $query = $this->getModelByAuthUser();

		if ($search) {
			$query->where(function ($q1) use ($search) {
				$q1->where('name', 'like', "%{$search}%")
					->orWhere('company', 'like', "%{$search}%")
					->orWhere('email', 'like', "%{$search}%");
			});
		}

        // You can chain more: ->where('type', 'admin')->orderBy('name')
        return parent::paginate($query, $perPage, $columns, $pageName, $page);
    }
}
