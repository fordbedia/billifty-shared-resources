<?php

use BilliftySDK\SharedResources\Modules\Dashboard\Http\Controllers\DashboardAnalyticsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
	Route::middleware('auth:api')->group(function () {
		Route::prefix('dashboard')->group(function () {
			Route::match(['get', 'post'], '/', [DashboardAnalyticsController::class, 'index']);
		});
	});
});
