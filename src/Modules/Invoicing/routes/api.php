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
Route::group(['prefix' => 'v1'], function(){
	Route::prefix('invoice')->group(function(){
		Route::get('/generated-invoice-number', [InvoiceController::class, 'generateInvoiceNumber']);
		Route::post('/save-draft', [InvoiceController::class, 'saveDraft']);
	});
	Route::get('template/category/{id}', [TemplateController::class, 'getTemplate']);
	Route::resource('business-profile', BusinessProfileController::class);
	Route::resource('client', ClientsController::class);
	Route::resource('currency', CurrencyController::class);
	Route::resource('template-category', TemplateCategoryController::class);
	Route::resource('template', TemplateController::class);
	Route::resource('color-scheme', ColorSchemeController::class);
});