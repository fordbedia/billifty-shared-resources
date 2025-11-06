<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests\StoreInvoiceRequest;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use BilliftySDK\SharedResources\Modules\Invoicing\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
	public function generateInvoiceNumber(InvoiceContracts $invoice)
	{
		return $invoice->autoInvoiceNumber();
	}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

	public function saveDraft(
		Request $request,
		StoreInvoiceRequest $storeInvoiceRequest,
		InvoiceService $invoiceService
	) {
		$data = $request->all();
		$invoiceService->create($data);

		return response()->json($data, 201);
	}
}
