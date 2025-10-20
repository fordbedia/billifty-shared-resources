<?php

namespace BilliftySDK\SharedResources\Modules\User\Database\Seeders;

use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use BilliftySDK\SharedResources\SDK\Database\MakeSeeder;

class UserSeeder extends MakeSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
				User::create([
					'name' => 'Ed Bedia',
					'email' => 'fordbedia@billifty.com',
					'password' => bcrypt('123456'),
				]);
    }

    /**
     * Revert the database seeds.
     */
    public function revert(): void
    {
        //
    }
}
