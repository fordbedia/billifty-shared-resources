<?php

use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\BusinessProfileController;
use BilliftySDK\SharedResources\Modules\User\Http\Controllers\AuthController;
use BilliftySDK\SharedResources\Modules\User\Http\Controllers\GoogleController;
use BilliftySDK\SharedResources\Modules\User\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
	Route::post('user/login', [AuthController::class, 'login']);

	Route::group(['middleware' => ['auth:api']], function () {
		Route::post('user/logout', [AuthController::class, 'logout'])
			->name('user.logout');
		Route::get('user/me', [UserController::class, 'me']);
		Route::post('user/{id}', [UserController::class, 'update']);;
	});
	Route::apiResource('user', UserController::class);
});

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('auth.google.redirect');

// Google callback handled by single AuthController
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])
    ->name('auth.google.callback');

// In-app login
Route::post('/auth/login', [AuthController::class, 'login'])
    ->name('auth.login');
