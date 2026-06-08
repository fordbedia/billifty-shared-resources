<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments\Traits\Security;

use BackedEnum;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;

trait ValidatesInvoiceState
{
	public function validateInvoiceState(Invoices $invoice)
	{
		$status = $invoice->status instanceof BackedEnum
			? $invoice->status->value
			: $invoice->status;

		if ($status === 'draft') {
			abort(403, 'Something went wrong. Invoice needs to be issued first.');
		} else if ($status === 'paid') {
			abort(403, 'This invoice is already paid. You cannot create a new payment link for it.');
		}
	}

	public function validateInvoiceDraftState(Invoices $invoice)
	{
		$status = $invoice->status instanceof BackedEnum
			? $invoice->status->value
			: $invoice->status;

		if ($status === 'draft') {
			abort(403, 'Something went wrong. Invoice needs to be issued first.');
		}
	}

	public function validateInvoicePaidState(Invoices $invoice, ?string $message = null)
	{
		$status = $invoice->status instanceof BackedEnum
			? $invoice->status->value
			: $invoice->status;

		if ($status === 'paid') {
			abort(403, $message ?? 'This invoice is already paid. You cannot create a new payment link for it.');
		}
	}
}
