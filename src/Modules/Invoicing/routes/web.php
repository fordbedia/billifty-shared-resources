<?php

use BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources\InvoiceResource;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Support\PlaywrightPdfRenderer;
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
			'renderContext' => 'pdf',
		])->render();

        $binary = app(PlaywrightPdfRenderer::class)->render($html, [
            'format' => 'A4',
            'landscape' => false,
            'printBackground' => true,
            'preferCSSPageSize' => true,
        ]);

		// Stream inline in browser (no download)
		$filename = 'invoice-' . $invoiceId . '.pdf';

		return response($binary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
	})->name('preview.invoice.pdf');

	// ----------------------------------------------------------------------------
	// Dev-only routes (never load during PHPUnit/Testbench)
	// ----------------------------------------------------------------------------
	$testingRoutes = __DIR__ . '/testing.php';

	if (app()->environment('local') && file_exists($testingRoutes)) {
		require $testingRoutes;
	}
});
