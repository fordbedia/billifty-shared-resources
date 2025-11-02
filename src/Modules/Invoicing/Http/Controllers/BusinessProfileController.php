<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\BusinessProfileContract;
use Illuminate\Http\Request;

class BusinessProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
			Request $request,
			BusinessProfileContract $businessProfile
		)
    {
        return $businessProfile->get();
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
