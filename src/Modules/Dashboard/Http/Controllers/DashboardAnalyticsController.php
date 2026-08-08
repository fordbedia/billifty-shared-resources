<?php

namespace BilliftySDK\SharedResources\Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Dashboard\Application\UseCases\GenerateDashboard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardAnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, GenerateDashboard $generateDashboard): JsonResponse
    {
		$request->validate([
			'period' => ['nullable', 'string', 'in:last-month,last-3-months,last-6-months,last-year,custom'],
			'start_date' => ['nullable', 'date_format:Y-m-d', 'required_with:end_date'],
			'end_date' => ['nullable', 'date_format:Y-m-d', 'required_with:start_date', 'after_or_equal:start_date'],
		]);

		return response()->json($generateDashboard->handle($request));
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
}
