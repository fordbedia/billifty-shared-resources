<?php

namespace BilliftySDK\SharedResources\TestCase\Builders;

use BilliftySDK\SharedResources\Modules\User\Models\User;
use BilliftySDK\SharedResources\TestCase\Concerns\CreateWorkspaceRecords;

class WorkspaceBuilder
{
	use CreateWorkspaceRecords;

	public function __construct(protected User $user)
	{}

	public static function make(User $user)
	{
		return new self($user);
	}
}