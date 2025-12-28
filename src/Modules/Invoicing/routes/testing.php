<?php
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources\InvoiceResource;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
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
		'invoice' => data_get($payload, 'data'),
		'category' => data_get($payload, 'data.template.category'),
		'colorScheme' => data_get($payload, 'data.colorScheme'),
		'renderContext' => 'pdf',   // let blade know it's for Dompdf
	])->render();

	/** @var \Barryvdh\DomPDF\PDF $pdf */
	$pdf = app('dompdf.wrapper');

	$pdf->loadHTML($html)
		->setPaper('A4', 'portrait');

	// Optional: turn on extra debugging ONLY in local
	if (app()->environment('local')) {
		$dompdf = $pdf->getDomPDF();
		// Uncomment if/when needed:
		// $dompdf->set_option('debugCss', true);
		// $dompdf->set_option('debugLayout', true);
		// $dompdf->set_option('debugLayoutLines', true);
		// $dompdf->set_option('debugLayoutBlocks', true);
		// $dompdf->set_option('debugLayoutInline', true);
		// $dompdf->set_option('debugLayoutPaddingBox', true);
		$dompdf->set_option('isRemoteEnabled', true);
	}

	// Stream inline in browser (no download)
	$filename = 'dev-invoice-' . $invoiceId . '.pdf';

	return $pdf->stream($filename, [
		'Attachment' => false, // open in browser, not download
	]);
})->name('dev.invoice.pdf');