<?php

use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\BusinessProfileController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\ClientsController;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::loginUsingId(1);
Route::group(['prefix' => 'v1'], function(){
	Route::resource('business-profile', BusinessProfileController::class);
	Route::resource('client', ClientsController::class);
	Route::get('invoice/generated-invoice-number', [InvoiceController::class, 'generateInvoiceNumber']);
});