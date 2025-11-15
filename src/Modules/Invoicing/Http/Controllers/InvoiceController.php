<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceAction;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests\StoreInvoiceRequest;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use BilliftySDK\SharedResources\Modules\Invoicing\Services\InvoiceService;
use BilliftySDK\SharedResources\SDK\Exception\ApiException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
		StoreInvoiceRequest $request,
		InvoiceService $invoiceService
	)
    {
        $data = $request->validated();

		$action = InvoiceAction::from($data['action']); // save_draft typical here
		$invoice = $invoiceService->upsert($data, $action, id: null);
		return response()->json($invoice, Response::HTTP_CREATED);
    }

	/**
	 * Display the specified resource.
	 * @throws ApiException
	 */
    public function show(string $id, InvoiceContracts $repo)
    {
		try {
			return $repo->findForUpdate($id);
		} catch (\Throwable $exception) {
			throw new ApiException($exception->getMessage(), 404);
		}
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
		StoreInvoiceRequest $request,
		InvoiceService $svc,
		string $id
	) {
		$data = $request->validated();
		$action = InvoiceAction::from($data['action']); // save_changes or issue
		$invoice = $svc->upsert($data, $action, (int) $id);
		return response()->json($invoice, Response::HTTP_OK);
	}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

	public function saveDraft(
		StoreInvoiceRequest $storeInvoiceRequest,
		InvoiceService $invoiceService
	) {
		$data = $storeInvoiceRequest->validated();

		$invoiceService->create($data);

		return response()->json($data, 201);
	}
}
