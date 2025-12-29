<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Billing\Domain\PlanFlowRedirection;
use Illuminate\Http\Request;

class PlanFlowRedirectionController extends Controller
{
    public function direction(Request $request)
	{
		return PlanFlowRedirection::{$request->source}()->value;
	}
}
