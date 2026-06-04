<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Repository;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Workspace;
use BilliftySDK\SharedResources\SDK\Database\RepositoryLayer;
use Illuminate\Support\Facades\Auth;

abstract class BaseRepository extends RepositoryLayer
{
	protected function defaultWorkspaceIdForAuthUser(): ?int
	{
		$userId = Auth::id();

		if (!$userId) {
			return null;
		}

		return Workspace::ensureDefaultForUser($userId)->getKey();
	}
}
