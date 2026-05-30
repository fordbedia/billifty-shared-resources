<?php

use BilliftySDK\SharedResources\Modules\Billing\Http\Controllers\InvoicePaymentController;
use BilliftySDK\SharedResources\Modules\Billing\Http\Controllers\PayPalPaymentController;

Route::post('pay/token/{token}', [InvoicePaymentController::class, 'getPaymentLink']);
Route::get('pay/preview/invoice/token/{token}', [InvoicePaymentController::class, 'preview'])->name('invoice.preview.link');
Route::match(['get', 'post'], 'pay/paypal/return/{paymentToken}', [PayPalPaymentController::class, 'handleReturn']);
Route::match(['get', 'post'], 'pay/paypal/cancel/{paymentToken}', [PayPalPaymentController::class, 'handleCancel']);
