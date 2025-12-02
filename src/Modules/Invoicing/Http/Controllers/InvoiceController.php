<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceAction;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests\StoreInvoiceRequest;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use BilliftySDK\SharedResources\Modules\Invoicing\Services\InvoiceService;
use BilliftySDK\SharedResources\SDK\Exception\ApiException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
	public function generateInvoiceNumber(InvoiceContracts $invoice)
	{
		return $invoice->autoInvoiceNumber();
	}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, InvoiceContracts $repo)
    {
		$dateRange = null;
		if ($request->start_date && $request->end_date) {
			$dateRange = ['start' => $request->start_date, 'end' => $request->end_date];
		}
		$search = null;
		if ($request->search) {
			$search = $request->search;
		}
		return $repo->paginate(dateRange: $dateRange, search: $search);
    }

	/**
	 * Store a newly created resource in storage.
	 * @throws ApiException
	 */
    public function store(
		StoreInvoiceRequest $request,
		InvoiceService $invoiceService
	) {
        $data = $request->validated();

		try {
			$action = InvoiceAction::from($data['action']); // save_draft typical here
			$invoice = $invoiceService->upsert($data, $action, id: null);
			return response()->json($invoice, Response::HTTP_CREATED);
		} catch (\Throwable $e) {
			$errors = ['errors' => [$e->getCode() => $e->getMessage()]];
			return response()->json($errors, Response::HTTP_INTERNAL_SERVER_ERROR);
		}
    }

	/**
	 * Display the specified resource.
	 * @throws ApiException
	 */
    public function show(string $id, InvoiceContracts $repo)
    {
		try {
			return $repo->findForUpdate($id)?->loadMissing(Invoices::relationships());
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

		try {
			$action = InvoiceAction::from($data['action']); // save_changes or issue
			$invoice = $svc->upsert($data, $action, (int)$id);
			return response()->json([...$invoice->toArray(), 'action' => InvoiceAction::actionStatus($data['action'])], Response::HTTP_OK);
		} catch (\Throwable $e) {
			$errors = ['errors' => [$e->getCode() => $e->getMessage()]];
			return response()->json($errors, Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, InvoiceContracts $repo)
    {
        return $repo->deleteInvoice($id);
    }

	public function saveDraft(
		StoreInvoiceRequest $storeInvoiceRequest,
		InvoiceService $invoiceService
	) {
		$data = $storeInvoiceRequest->validated();

		$invoiceService->create($data);

		return response()->json($data, 201);
	}

	public function generate(
        int $id,
        InvoiceContracts $invoices
    ) {
        $invoice = $invoices->findForUpdate($id); // user-scoped, with Auth
        // ... mark as issued, save, etc.
		$invoice->forceFill([
			'pdf_status' => 'queued',
			'pdf_error'  => null,
		])->save();

        // queue PDF generation
        $invoices->queuePdfGeneration($invoice);

        return response()->json([
            'data' => $invoice->fresh(), // or resource
        ]);
    }

	public function download(int $id, InvoiceContracts $invoices)
	{
		$invoice = $invoices->findForUpdate($id); // user-scoped

		if ($invoice->pdf_status !== 'ready' || !$invoice->pdf_path) {
			abort(404, 'Invoice PDF not ready yet.');
		}

		$disk = Storage::disk($invoice->pdf_disk ?? 'public');

		if (!$disk->exists($invoice->pdf_path)) {
			abort(404, 'Invoice PDF file is missing.');
		}

		// Build nice filename: INV-0001_Client-Name.pdf
		$number = $invoice->invoice_number ?? ('invoice-' . $invoice->getKey());
		$client = $invoice->client->name ?? null;

		$base = $client
			? Str::slug("{$number}_{$client}", '_')
			: Str::slug($number, '_');

		$filename = "{$base}.pdf";

		$absolutePath = $disk->path($invoice->pdf_path);

		return response()->download($absolutePath, $filename, [
			'Content-Type' => 'application/pdf',
		]);
	}
}
