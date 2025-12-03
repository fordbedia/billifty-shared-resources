<?php

use BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources\InvoiceResource;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
	Route::get('/dev/invoices/{invoice}/preview', function ($invoiceId) {
		$invoice = Invoices::with(Invoices::relationships())
			->findOrFail($invoiceId);
		// Massage colors relationship
		if ($invoice->colorScheme) {
			$invoice->colorScheme->setRelation('colors', $invoice->colorScheme->colors->keyBy('name'));
		}

		$payload = (new InvoiceResource($invoice))->response()->getData();

		return view("invoicing::templates.show", [
			'invoice' => data_get($payload, 'data'),
			'category' => data_get($payload, 'data.template.category'), // or 'Classic' / 'Minimal'
			'colorScheme' => data_get($payload, 'data.colorScheme'),
			'renderContext' => 'html'
		]);
	})->name('dev.invoice.preview');

	// SIGNED URL: PUBLIC PREVIEW
	// ----------------------------------------------------------------------------
	// /api/v1 route is executing via signed route.
	// ----------------------------------------------------------------------------
    Route::get('/preview/invoice/{invoice}', function (Invoices $invoice) {
        // Same logic as dev preview, but using route-model binding
        $invoice->load(Invoices::relationships());

        if ($invoice->colorScheme) {
            $invoice->colorScheme->setRelation(
                'colors',
                $invoice->colorScheme->colors->keyBy('name')
            );
        }

        $payload = (new InvoiceResource($invoice))->response()->getData();

        return view('invoicing::templates.show', [
            'invoice'     => data_get($payload, 'data'),
            'category'    => data_get($payload, 'data.template.category'),
            'colorScheme' => data_get($payload, 'data.colorScheme'),
			'renderContext' => 'html'
        ]);
    })
        ->middleware('signed') // only valid if URL has a correct signature
        ->name('invoice.preview'); // used when generating the signed URL

	Route::get('/preview/invoice/{invoice}/pdf', function (int $invoiceId) {
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
		$filename = 'invoice-' . $invoiceId . '.pdf';

		return $pdf->stream($filename, [
			'Attachment' => false, // open in browser, not download
		]);
	})->name('preview.invoice.pdf');

	// ----------------------------------------------------------------------------
	// For testing PDF
	// ----------------------------------------------------------------------------
	if (config('app.env') === 'local') {
		require base_path('../shared-resources/src/Modules/Invoicing/routes/testing.php');
	}
});