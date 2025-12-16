<?php

use BilliftySDK\SharedResources\Modules\Billing\Http\Controllers\BillingController;
use BilliftySDK\SharedResources\Modules\Billing\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
	Route::prefix('v1')->group(function () {
		Route::middleware('auth:api')->group(function () {
			// Create subscription + PaymentIntent for Payment Element
			Route::post('billing/subscription-intent', [BillingController::class, 'createSubscriptionIntent']);
			// Create Stripe Billing Portal session (manage saved cards)
			Route::post('/billing/portal-session', [BillingController::class, 'createPortalSession']);
			Route::post('/billing/confirm-subscription', [BillingController::class, 'confirmSubscription']);
		});

		Route::prefix('/billing')->group(function () {
			Route::post('/subscription/change-plan', [BillingController::class, 'changePlan']);
			Route::post('/subscription/cancel', [BillingController::class, 'cancelSubscription']);

			Route::post('/payment-method/setup-intent', [BillingController::class, 'createPaymentMethodSetupIntent']);
			Route::post('/payment-method/update', [BillingController::class, 'updatePaymentMethod']);
			Route::post('/portal-session', [BillingController::class, 'createPortalSession']);
		});
	});
});

Route::prefix('v1')->group(function () {
	Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
});