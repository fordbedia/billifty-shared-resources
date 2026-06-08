<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers;

use BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments\Traits\Security\ValidatesInvoiceState;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources\InvoiceResource;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources\PaymentLinkResource;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\PaymentLinkRepository;
use Illuminate\Routing\Controller;
use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceAction;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests\StoreInvoiceRequest;
use BilliftySDK\SharedResources\Modules\Invoicing\Jobs\GenerateInvoicePdfJob;
use BilliftySDK\SharedResources\Modules\Invoicing\Jobs\SendInvoiceCopyToBusinessProfileJob;
use BilliftySDK\SharedResources\Modules\Invoicing\Jobs\SendToClientJob;
use BilliftySDK\SharedResources\Modules\Invoicing\Mail\InvoiceSendMailToBusinessProfile;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use BilliftySDK\SharedResources\Modules\Invoicing\Services\InvoiceService;
use BilliftySDK\SharedResources\SDK\Exception\ApiException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
	use ValidatesInvoiceState;

	protected array $lockedColumns = [
		''
	];

	public function __construct()
	{
		$this->middleware(['plan.limit:max_invoices_per_month'])->only(['store', 'generate', 'sendToBusinessProfile', 'sendToClient']);;
	}

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
		InvoiceService      $invoiceService
	)
	{
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
			return $repo->findById($id)?->loadMissing(Invoices::relationships());
		} catch (\Throwable $exception) {
			throw new ApiException($exception->getMessage(), 404);
		}
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(
		StoreInvoiceRequest $request,
		InvoiceService      $svc,
		InvoiceContracts 	$invoices,
		string              $id
	)
	{
		// ----------------------------------------------------------------------------
		// Add a guard to prevent updating a paid invoice.
		// ----------------------------------------------------------------------------
		$invoice = $invoices->findById($id);
		$this->validateInvoicePaidState($invoice, 'Error: You cannot update a paid invoice.');

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
		$invoice = $repo->findById($id);
		if ($repo->deleteInvoice($id)) {
			return response()->json($invoice);
		}
		return response()->json(['message' => 'Invoice not found'], 404);
	}

	public function saveDraft(
		StoreInvoiceRequest $storeInvoiceRequest,
		InvoiceService      $invoiceService
	)
	{
		$data = $storeInvoiceRequest->validated();

		$invoiceService->create($data);

		return response()->json($data, 201);
	}

	public function generate(
		int              $id,
		InvoiceContracts $invoices
	)
	{
		$invoice = $invoices->findById($id); // user-scoped, with Auth

		// Do not queue PDFs for invoices that are not allowed to be downloaded.
		$this->validateInvoiceDraftState($invoice);

		// ... mark as issued, save, etc.
		$invoice->forceFill([
			'pdf_status' => 'queued',
			'pdf_error' => null,
		])->save();

		// queue PDF generation
		GenerateInvoicePdfJob::dispatch($invoice->id, false, (int)Auth::id());

		return response()->json([
			'data' => $invoice->fresh(), // or resource
		]);
	}

	public function download(int $id, InvoiceContracts $invoices)
	{
		$invoice = $invoices->findById($id); // user-scoped

		// Check if invoice is valid for download.
		$this->validateInvoiceDraftState($invoice);

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
			'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
			'Pragma' => 'no-cache',
			'Expires' => '0',
		]);
	}

	/**
	 * @param int $id
	 * @param Request $request
	 * @param InvoiceContracts $invoices
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function sendToBusinessProfile(int $id, Request $request, InvoiceContracts $invoices)
	{
		$userId = Auth::user()->id;
		$invoice = $invoices->findById($id, $userId);
		$hasCsvReport = $request->boolean('hasCsvReport');

		Bus::chain([
			new GenerateInvoicePdfJob($invoice->id, $hasCsvReport, $userId),
			new SendInvoiceCopyToBusinessProfileJob($invoice->id, $userId),
		])->dispatch();

		return response()->json([
			'message' => 'Invoice copy has been queued for sending to your email.',
		]);
	}

	/**
	 * @param int $id
	 * @param Request $request
	 * @param InvoiceContracts $invoices
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function sendToClient(int $id, Request $request, InvoiceContracts $invoices)
	{
		$userId = Auth::user()->id;
		$invoice = $invoices->findById($id, $userId);

		$userMessage = $request->input('message');

		Bus::chain([
			new GenerateInvoicePdfJob($invoice->id, false, $userId),
			new SendToClientJob($invoice->id, $userMessage, $userId)
		])->dispatch();

		return response()->json([
			'message' => 'Invoice copy has been queued for sending to your email.',
		]);
	}

	public function preview(
		Request          $request,
		InvoiceContracts $invoice
	)
	{
		$invoice = $invoice->findById($request->id)?->loadMissing(Invoices::relationships());

		if ($invoice->colorScheme) {
			$invoice->colorScheme->setRelation(
				'colors',
				$invoice->colorScheme->colors->keyBy('name')
			);
		}

		$payload = (new InvoiceResource($invoice))->response()->getData();

		return response()
			->view('invoicing::templates.show', [
				'invoiceModel' => $invoice,
				'invoice' => $payload,
				'category' => data_get($payload, 'template.category'),
				'colorScheme' => data_get($payload, 'colorScheme'),
				'renderContext' => 'public-html',
			]);
	}

	public function publicInvoiceLink(
		Request          $request,
		InvoiceContracts $invoice
	)
	{
		$invoice = $invoice->findById($request->id)?->loadMissing(Invoices::relationships());

		return new PaymentLinkResource($invoice->paymentLink);
	}

	public function revokeInvoicePublicLink(
		Request               $request,
		InvoiceContracts      $invoice,
		PaymentLinkRepository $paymentLinkRepository
	)
	{
		$invoice = $invoice->findById($request->id)?->loadMissing(Invoices::relationships());

		if (!$invoice) {
			abort(404, "Not Authorized to perform this action");
		}

		$paymentLinkRepository->revoke($request->id);
	}

	public function renewInvoicePublicLink(
		Request               $request,
		InvoiceContracts      $invoice,
		PaymentLinkRepository $paymentLinkRepository
	)
	{
		$invoice = $invoice->findById($request->id)?->loadMissing(Invoices::relationships());

		if (!$invoice) {
			abort(404, "Not Authorized to perform this action");
		}

		$paymentLinkRepository->renew($request->id);
	}
}
