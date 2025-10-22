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
			'theme' => $invoice->theme_json ?? [],
			'schemeName' => $invoice->theme_json['scheme'] ?? 'Ocean Blue',
			'categoryName' => 'Modern', // or 'Classic' / 'Minimal'
			'scheme' => $invoice->colorScheme->color_scheme_name ?? '',
		]);
	})->name('dev.invoice.preview');
});