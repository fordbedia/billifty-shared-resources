<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Enums;

enum PaymentMethod: string
{
		case BANK_PAYMENT = 'bank_transfer';
    case PAYPAL       = 'paypal';
    case CASH_APP     = 'cash_app';
    case STRIPE       = 'stripe';

    public function label(): string
    {
        return match ($this) {
            self::BANK_PAYMENT => 'Bank Transfer',
            self::PAYPAL       => 'PayPal',
            self::CASH_APP     => 'Cash App',
            self::STRIPE       => 'Stripe',
        };
    }
}
