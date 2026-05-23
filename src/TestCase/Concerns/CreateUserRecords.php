<?php

namespace BilliftySDK\SharedResources\TestCase\Concerns;

use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\User;

trait CreateUserRecords
{
	public function create(?Plan $plan = null)
	{
		return User::updateOrCreate(['email' => 'johndoe+test1@gmail.com'], [
			'plan_id' => $plan ?? $this->plan->id,
			'fname' => 'John',
			'lname' => 'Doe',
			'name' => 'John Doe',
			'email_verified_at' => now(),
			'password' => bcrypt('123456'),
			'is_test' => 1
		]);
	}
}
