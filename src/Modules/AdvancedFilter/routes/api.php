<?php

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Http\Controllers\InvoiceAdvancedFilterInputController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
	Route::middleware('auth:api')->group(function () {
		Route::prefix('advanced-filter')->group(function () {
			Route::apiResource('invoice-module', InvoiceAdvancedFilterInputController::class);
			Route::get('options', [InvoiceAdvancedFilterInputController::class, 'options']);
		});
	});
});
