<?php

namespace BilliftySDK\SharedResources\TestCase\Concerns;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Clients;

trait CreateClientRecords
{
	public function create()
	{
		return Clients::create([
			// Clients are scoped by workspace; the workspace carries user ownership.
			'workspace_id' => $this->user->resolveDefaultWorkspace()->id,
			'name' => 'Cris Pepper',
			'is_test' => 1
		]);
	}
}
