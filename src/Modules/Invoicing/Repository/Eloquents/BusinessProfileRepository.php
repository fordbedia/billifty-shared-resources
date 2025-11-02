<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\BusinessProfiles;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\BaseRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\BusinessProfileContract;

class BusinessProfileRepository extends BaseRepository implements BusinessProfileContract
{
	public function get()
	{
		return $this->getByUser()->get();
	}

	public function makeModel(): string
	{
		return BusinessProfiles::class;
	}
}