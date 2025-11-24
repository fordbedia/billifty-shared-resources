<?php

use BilliftySDK\SharedResources\Modules\User\Http\Controllers\AuthController;
use BilliftySDK\SharedResources\Modules\User\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('auth.google.redirect');

// Google callback handled by single AuthController
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])
    ->name('auth.google.callback');

// In-app login
Route::post('/auth/login', [AuthController::class, 'login'])
    ->name('auth.login');
