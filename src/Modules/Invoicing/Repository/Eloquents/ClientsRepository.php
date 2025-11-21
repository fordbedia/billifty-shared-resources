<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Clients;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\BaseRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\ClientsContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientsRepository extends BaseRepository implements ClientsContract
{
	public function all()
	{
		return $this->getByUser()->get();
	}

	public function makeModel(): string
	{
		return Clients::class;
	}

	public function findById(int $id): Model
	{
		return $this->getByUser()->whereKey($id)->firstOrFail();
	}

	public function save(array $data, int $id = null): Model
	{
		DB::beginTransaction();
		if ($id && $id === (int)$data['id']) {
			$model = $this->getByUser()->findOrFail($id);
		} else {
			$model = new Clients($data);
		}

		$model->user_id = Auth::user()->id;

		if ($model->exists()) {
			$model->fill($data);
		}

		$model->save();
		DB::commit();
		return $model->refresh();
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
        $query = $this->getByUser();

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