<?php

namespace BilliftySDK\SharedResources\Modules\User\Traits;

use BilliftySDK\SharedResources\Modules\User\Models\PlanCapability;
use Illuminate\Support\Facades\Auth;

trait Capabilities
{
	public function relationships(string $key): ?string
	{
		$capability = PlanCapability::where('key', $key)->first();

		if ($capability) {
			return $capability->model_relationship;
		}
		return null;
	}

	protected function get()
	{
	}
}