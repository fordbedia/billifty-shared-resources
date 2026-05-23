<?php

namespace BilliftySDK\SharedResources\TestCase\Builders;

use BilliftySDK\SharedResources\TestCase\Concerns\CreatePlanRecords;

class PlanBuilder
{
	use CreatePlanRecords;

	public static function make()
	{
		return new self();
	}
}