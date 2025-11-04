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
}