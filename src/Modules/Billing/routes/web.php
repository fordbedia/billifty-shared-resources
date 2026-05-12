<?php

use BilliftySDK\SharedResources\Modules\Billing\Http\Controllers\InvoicePaymentController;

Route::post('pay/token/{token}', [InvoicePaymentController::class, 'getPaymentLink']);