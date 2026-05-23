<?php

namespace BilliftySDK\SharedResources\TestCase\Builders;

use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\TestCase\Concerns\CreateUserRecords;

class UserBuilder
{
	use CreateUserRecords;

	public function __construct(protected Plan $plan)
	{}

	public static function make(Plan $plan)
	{
		return new self($plan);
	}
}
