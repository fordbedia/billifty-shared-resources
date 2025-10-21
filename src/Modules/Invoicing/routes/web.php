<?php
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
	Route::get('/dev/invoices/{invoice}/preview', function () {
		$invoice = \BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices::with(['businessProfile', 'client', 'items'])->find(2);

		return view('invoicing::categories.boilerplate', [
			'invoice' => $invoice->load(['businessProfile', 'client', 'items']),
			'theme' => $invoice->theme_json ?? [],
			'schemeName' => $invoice->theme_json['scheme'] ?? 'Ocean Blue',
			'categoryName' => 'Modern', // or 'Classic' / 'Minimal'
		]);
	})->name('dev.invoice.preview');
});