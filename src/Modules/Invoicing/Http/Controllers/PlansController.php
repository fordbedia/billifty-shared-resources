<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Invoicing\Enums\PlanCode;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
		$plans = Plan::with('capabilities')
			->orderBy('sort_order')
			->orderBy('id')
			->get()
			->map(fn (Plan $plan) => $this->planToArray($plan))
			->values();

		return response()->json($plans);
    }

	private function planToArray(Plan $plan): array
	{
		return PlanCode::fromPlan($plan);
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

	public function lookUpPlan(Request $request)
	{
		$plans = Plan::with('capabilities')
			->orderBy('sort_order')
			->orderBy('id')
			->get()
			->map(fn (Plan $plan) => $plan->code === trim($request->plan) ? $this->planToArray($plan) : null)
			->filter()
			->values()
			->flatMap(fn ($p) => [...$p]);

		return response()->json($plans);
	}
}
