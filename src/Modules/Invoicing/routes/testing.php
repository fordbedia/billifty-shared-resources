<?php
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources\InvoiceResource;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Support\PlaywrightPdfRenderer;
use Illuminate\Support\Facades\Route;

Route::get('/dev/invoices/{invoiceId}/pdf', function (int $invoiceId) {
	/** @var \BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices $invoice */
	$invoice = Invoices::with(Invoices::relationships())
		->findOrFail($invoiceId);

	// Same color scheme massage
	if ($invoice->colorScheme) {
		$invoice->colorScheme->setRelation(
			'colors',
			$invoice->colorScheme->colors->keyBy('name')
		);
	}

	// Build payload exactly like the resource does
	$payload = (new InvoiceResource($invoice))
		->response()
		->getData();

	// Render the same Blade view, but tell it we're in "pdf" mode
	$html = view('invoicing::templates.show', [
		'invoice' => $payload,
		'category' => data_get($payload, 'template.category'),
		'colorScheme' => data_get($payload, 'colorScheme'),
		'renderContext' => 'pdf',
	])->render();

	$binary = app(PlaywrightPdfRenderer::class)->render($html, [
		'format' => 'A4',
		'landscape' => false,
		'printBackground' => true,
		'preferCSSPageSize' => true,
	]);

	// Stream inline in browser (no download)
	$filename = 'dev-invoice-' . $invoiceId . '.pdf';

	return response($binary, 200, [
		'Content-Type' => 'application/pdf',
		'Content-Disposition' => 'inline; filename="' . $filename . '"',
	]);
})->name('dev.invoice.pdf');
