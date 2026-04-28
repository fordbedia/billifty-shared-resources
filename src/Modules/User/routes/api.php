<?php

use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\BusinessProfileController;
use BilliftySDK\SharedResources\Modules\User\Http\Controllers\AuthController;
use BilliftySDK\SharedResources\Modules\User\Http\Controllers\GoogleController;
use BilliftySDK\SharedResources\Modules\User\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::prefix('v1')->group(function () {
	Route::post('user/login', [AuthController::class, 'login']);

	Route::group(['middleware' => ['auth:api']], function () {
		Route::post('user/logout', [AuthController::class, 'logout'])
			->name('user.logout');
		Route::get('user/me', [UserController::class, 'me']);
		Route::post('user/change-password', [UserController::class, 'changePassword']);
		Route::post('user/{id}', [UserController::class, 'update']);;
	});
	Route::apiResource('user', UserController::class);

	Route::post('/user/email/verification-notification', function (Request $request) {
		if ($request->user()->hasVerifiedEmail()) {
			return response()->json([
				'message' => 'Email is already verified.',
			]);
		}

		$request->user()->sendEmailVerificationNotification();

		return response()->json([
			'message' => 'Verification link sent.',
		]);
	})->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');
});

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('auth.google.redirect');

// Google callback handled by single AuthController
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])
    ->name('auth.google.callback');

// In-app login
Route::post('/auth/login', [AuthController::class, 'login'])
    ->name('auth.login');
