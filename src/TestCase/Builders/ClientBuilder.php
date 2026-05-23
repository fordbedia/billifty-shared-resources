<?php

namespace BilliftySDK\SharedResources\TestCase\Builders;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Clients;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use BilliftySDK\SharedResources\TestCase\Concerns\CreateClientRecords;

class ClientBuilder
{
	use CreateClientRecords;

	public function __construct(protected User $user)
	{}

	public static function make(User $user)
	{
		return new self($user);
	}
}