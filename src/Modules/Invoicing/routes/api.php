<?php

use BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers\BusinessProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::loginUsingId(1);
Route::group(['prefix' => 'v1'], function(){
	Route::resource('business-profile', BusinessProfileController::class);
});