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
		]);
	})->name('dev.invoice.preview');
});