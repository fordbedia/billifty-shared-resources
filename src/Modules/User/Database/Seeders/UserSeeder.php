<?php

namespace BilliftySDK\SharedResources\Modules\User\Database\Seeders;

use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use BilliftySDK\SharedResources\SDK\Database\MakeSeeder;

class UserSeeder extends MakeSeeder
{
	protected array $users = [
		[
			'email' => 'john+free@billifty.czom',
			'name' => 'John Paine',
			'plan_id' => 1,
			'is_test' => 1
		],
		[
			'email' => 'kirk+pro@billifty.com',
			'name' => 'Kirk McDonald',
			'plan_id' => 2,
			'is_test' => 1
		],
		[
			'email' => 'james+premium@billifty.com',
			'name' => 'James Harris',
			'plan_id' => 3,
			'is_test' => 1
		],
		[
			'email' => 'fordbedia@billifty.com',
			'name' => 'Ed Bedia',
			'plan_id' => 3,
			'is_test' => 1
		]
	];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		foreach($this->users as $user) {
			User::updateOrCreate(array_merge($user, ['password' => bcrypt('123456')]));
		}
    }

    /**
     * Revert the database seeds.
     */
    public function revert(): void
    {
        //
    }
}
