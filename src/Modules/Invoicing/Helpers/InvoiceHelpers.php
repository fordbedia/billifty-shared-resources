<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Helpers;

class InvoiceHelpers
{
	/**
	 * Increment the last numeric sequence in an invoice number string.
	 * Supports formats like:
	 *  - INV-0001   → INV-0002
	 *  - BBIV-221   → BBIV-222
	 *  - 0912-343-11-234 → 0912-343-11-235
	 */
	public static function incrementInvoiceNumber(string $invoiceNumber): string
	{
		// Use regex to find the last continuous group of digits in the string
		if (preg_match('/(\d+)(?!.*\d)/', $invoiceNumber, $matches)) {
			$numberPart = $matches[1];
			$length = strlen($numberPart);

			// Increment and preserve leading zeros
			$incremented = str_pad(((int)$numberPart) + 1, $length, '0', STR_PAD_LEFT);

			// Replace only the last occurrence
			$newInvoice = preg_replace('/(\d+)(?!.*\d)/', $incremented, $invoiceNumber);

			return $newInvoice;
		}

		// If no digits found, start from 1
		return $invoiceNumber . '-001';
	}

}