<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Clients;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\BaseRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\ClientsContract;

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
        $query = $this->getByUser()->whereNull('archived_at');

//		if ($search) {
//			$query->where(function ($query) use ($search) {
//				$query->where('name', 'like', "%{$search}%")
//					->orWhere('email', 'like', "%{$search}%");
//			});
//		}

        // You can chain more: ->where('type', 'admin')->orderBy('name')
        return parent::paginate($query, $perPage, $columns, $pageName, $page);
    }
}