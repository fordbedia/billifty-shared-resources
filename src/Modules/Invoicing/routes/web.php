<?php

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
		return view("invoicing::templates.show", [
			'invoice' => $invoice,
			'category' => $invoice->template->category, // or 'Classic' / 'Minimal'
			'colorScheme' => $invoice->colorScheme ?? '',
		]);
	})->name('dev.invoice.preview');
});