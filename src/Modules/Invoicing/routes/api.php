<?php

use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\BusinessProfileController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\ClientsController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::loginUsingId(1);
Route::group(['prefix' => 'v1'], function(){
	Route::prefix('invoice')->group(function(){
		Route::get('/generated-invoice-number', [InvoiceController::class, 'generateInvoiceNumber']);
		Route::post('/save-draft', [InvoiceController::class, 'saveDraft']);
	});
	Route::resource('business-profile', BusinessProfileController::class);
	Route::resource('client', ClientsController::class);
});