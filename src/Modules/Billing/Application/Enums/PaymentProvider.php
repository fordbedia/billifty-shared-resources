<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Application\Enums;

enum PaymentProvider: string
{
	case STRIPE = 'stripe';
    case PAYPAL = 'paypal';
    case CASH_APP = 'cash_app';
    case BANK_TRANSFER = 'bank_transfer';
}
