<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments\Traits\Security;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;

trait ValidatePaymentLink
{
	public function validateRevokedPaymentLink(Invoices $invoice)
	{
		if ($invoice->paymentLink->public_token_revoked_at) {
			abort(403, 'This payment link has been revoked.');
		}
	}

	public function validateExpiredPaymentLink(Invoices $invoice)
	{
		if ($invoice->paymentLink->public_token_expires_at) {
			abort(403, 'This payment link has expired.');
		}
	}
}