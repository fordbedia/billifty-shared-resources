<?php

use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\BusinessProfileController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\ClientsController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\ColorSchemeController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\CurrencyController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\InvoiceController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\TemplateCategoryController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::loginUsingId(1);
Route::group(['prefix' => 'v1'], function () {
	Route::prefix('invoice')->group(function () {
		Route::get('/generated-invoice-number', [InvoiceController::class, 'generateInvoiceNumber']);
		Route::post('/save-draft', [InvoiceController::class, 'saveDraft']);
	});

	Route::get('business-profile/get-all', [BusinessProfileController::class, 'getAll']);
	Route::apiResource('invoice', InvoiceController::class);
	Route::get('template/category/{id}', [TemplateController::class, 'getTemplate']);
	Route::apiResource('business-profile', BusinessProfileController::class);
	Route::apiResource('client', ClientsController::class);
	Route::apiResource('currency', CurrencyController::class);
	Route::apiResource('template-category', TemplateCategoryController::class);
	Route::apiResource('template', TemplateController::class);
	Route::apiResource('color-scheme', ColorSchemeController::class);
});