<?php

namespace BilliftySDK\SharedResources\TestCase\Concerns;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\BusinessProfiles;

trait CreateBusinessProfileRecords
{
	public function create()
	{
		return BusinessProfiles::create([
			'user_id' => $this->user->id,
			'name' => 'JDoe Trading LLC',
			'legal_name' => 'JDoe Trading LLC',
			'email' => 'business@jdoetest.com',
			'logo_disk' => 'public',
			'is_test' => 1
		]);
	}
}