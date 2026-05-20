<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments\Traits\Security;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;

trait ValidatesInvoiceState
{
	public function invoiceIssued(Invoices $invoice)
	{
		if ($invoice->status !== 'issued') {
			abort(403, 'Something went wrong. Invoice needs to be issued first.');
		}
	}
}