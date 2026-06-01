<?php

use BilliftySDK\SharedResources\Modules\Invoicing\Action\GenerateInvoicePdf;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\BusinessProfileController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\ClientsController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\ColorSchemeController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\CurrencyController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\InvoiceController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\PlansController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\TemplateCategoryController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\TemplateController;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

//Auth::loginUsingId(1);
Route::prefix('v1')->group(function () {

	Route::middleware('auth:api')->group(function () {
		Route::prefix('invoice')->group(function () {
			Route::get('/generated-invoice-number', [InvoiceController::class, 'generateInvoiceNumber']);
			Route::post('/save-draft', [InvoiceController::class, 'saveDraft']);
			Route::post('/{invoice}/pdf', [InvoiceController::class, 'generate'])
				->name('invoices.pdf.generate');
			Route::get('/{invoice}/pdf/download', [InvoiceController::class, 'download'])
                ->name('invoices.pdf.download');
			Route::post('/{invoice}/pdf/send-copy-to-business-profile', [InvoiceController::class, 'sendToBusinessProfile'])
                ->name('invoices.pdf.send-copy-to-business-profile');
			Route::post('/{invoice}/pdf/send-to-client', [InvoiceController::class, 'sendToClient'])
                ->name('invoices.pdf.send-to-client');
			Route::get('/{id}/preview/settings', [InvoiceController::class, 'preview']);
			Route::get('/{id}/preview/public-invoice-link', [InvoiceController::class, 'publicInvoiceLink']);
			Route::post('/{id}/preview/public-invoice-link/revoke', [InvoiceController::class, 'revokeInvoicePublicLink']);
			Route::post('/{id}/preview/public-invoice-link/renew', [InvoiceController::class, 'renewInvoicePublicLink']);
		});

		Route::get('business-profile/get-all', [BusinessProfileController::class, 'getAll']);
		Route::post('business-profile/archive/{id}', [BusinessProfileController::class, 'archive']);
		// Client
		Route::get('client/paginate', [ClientsController::class, 'paginate']);
		Route::apiResource('invoice', InvoiceController::class);
		Route::get('template/category/{id}', [TemplateController::class, 'getTemplate']);
		Route::apiResource('business-profile', BusinessProfileController::class);
		Route::apiResource('client', ClientsController::class);
		Route::apiResource('currency', CurrencyController::class);
		Route::apiResource('template-category', TemplateCategoryController::class);
		Route::apiResource('template', TemplateController::class);
		Route::apiResource('color-scheme', ColorSchemeController::class);
	});
	Route::apiResource('plan', PlansController::class);
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('/v1/invoice/{invoice}/preview-url', function (Request $request, Invoices $invoice) {
        // Guard: only allow owner to get a preview link
        if ((int) $invoice->workspace?->user_id !== (int) $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        // Permanent signed link:
        $url = URL::signedRoute('invoice.preview', [
            'invoice' => $invoice->id,
        ]);

        // Or temporary (expires in 30 minutes):
        // $url = URL::temporarySignedRoute('invoice.preview', now()->addMinutes(30), [
        //     'invoice' => $invoice->id,
        // ]);

        return response()->json([
            'url' => $url,
        ]);
    })->name('api.invoice.preview-url');

	Route::get('/v1/invoice/{invoice}/preview-pdf', function (Request $request, Invoices $invoice) {
        // Guard: only allow owner to get a preview link
        if ((int) $invoice->workspace?->user_id !== (int) $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        // Permanent signed link:
        $url = URL::signedRoute('preview.invoice.pdf', [
            'invoice' => $invoice->id,
        ]);

        // Or temporary (expires in 30 minutes):
        // $url = URL::temporarySignedRoute('invoice.preview', now()->addMinutes(30), [
        //     'invoice' => $invoice->id,
        // ]);

        return response()->json([
            'url' => $url,
        ]);
    })->name('api.invoice.preview-pdf');
});
