<?php

namespace BilliftySDK\SharedResources\TestCase\Concerns;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Workspace;
use BilliftySDK\SharedResources\Modules\User\Models\User;

trait CreateWorkspaceRecords
{
	public function create(User|null $user = null)
	{
		return Workspace::create([
			'user_id' => $user?->id ?? $this->user->id,
			'name' => 'default',
			'is_active' => 1,
			'is_default' => 1
		]);
	}
}