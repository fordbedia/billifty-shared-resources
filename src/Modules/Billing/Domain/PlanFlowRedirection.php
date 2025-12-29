<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Domain;

use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use Illuminate\Support\Facades\Facade;

class PlanFlowRedirection extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'billifty.plan_flow_redirection';
	}
}