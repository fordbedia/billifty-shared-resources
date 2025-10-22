<?php
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
	Route::get('/dev/invoices/{invoice}/preview', function ($invoiceId) {
		$invoice = \BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices::with([
			'businessProfile',
			'client',
			'items',
			'colorScheme',
			'template'
		])
			->find($invoiceId);

		return view("invoicing::templates.show", [
			'invoice' => $invoice,
			'category' => $invoice->template->category, // or 'Classic' / 'Minimal'
			'schemeName' => $invoice->colorScheme->slug ?? '',
		]);
	})->name('dev.invoice.preview');
});