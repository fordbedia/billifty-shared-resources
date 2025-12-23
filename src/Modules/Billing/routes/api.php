<?php

use BilliftySDK\SharedResources\Modules\Billing\Http\Controllers\BillingController;
use BilliftySDK\SharedResources\Modules\Billing\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::post('billing/checkout-session', [BillingController::class, 'createCheckoutSession']);
        Route::post('billing/portal-session', [BillingController::class, 'createPortalSession']);

        // auto-cancel when user selects Free
        Route::post('billing/cancel', [BillingController::class, 'cancelMySubscription']);
		Route::post('billing/confirm-checkout', [BillingController::class, 'confirmCheckout']);
		Route::post('billing/confirm-subscription', [BillingController::class, 'confirmSubscription']);
    });

    Route::post('stripe/webhook', [StripeWebhookController::class, 'handle']);
});
