<?php

namespace BilliftySDK\SharedResources\TestCase\Concerns;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Clients;
use BilliftySDK\SharedResources\Modules\User\Models\User;

trait CreateClientRecords
{
	public function create()
	{
		return Clients::create([
			'user_id' => $this->user->id,
			'name' => 'Cris Pepper',
			'is_test' => 1
		]);
	}
}