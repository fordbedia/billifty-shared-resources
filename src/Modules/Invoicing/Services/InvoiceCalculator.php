<?php
/**
 * @Author: Ed Bedia
 * @email: fordbedia@gmail.com
 * @Usage:
 * $invoice->load('items');
 * app(InvoiceCalculator::class)->compute($invoice);
 * $invoice->push(); // saves invoice + items
 *
 */

namespace BilliftySDK\SharedResources\Modules\Invoicing\Services;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;

final class InvoiceCalculator
{
    public function compute(Invoices $invoice): Invoices
    {
        $subtotal = 0;

        foreach ($invoice->items as $it) {
            // quantity can be decimal; round at cent precision
            $line = (int) round(($it->quantity ?? 0) * ($it->unit_price_cents ?? 0), 0);
            $it->line_total_cents = $line;
            $subtotal += $line;
        }

        $discount  = (int) ($invoice->discount_cents ?? 0);
        $shipping  = (int) ($invoice->shipping_cents ?? 0);

        // If using a tax rate in basis points (bps):
        // $invoice->tax_rate_bps = 825; // 8.25% = 825 bps
        $tax = (int) ($invoice->tax_cents ?? 0);
        if (!isset($invoice->tax_cents) && isset($invoice->tax_rate_bps)) {
            $tax = (int) round($subtotal * $invoice->tax_rate_bps / 10000);
        }

        $invoice->subtotal_cents = $subtotal;
        $invoice->tax_cents      = $tax;
        $invoice->total_cents    = max(0, $subtotal - $discount + $tax + $shipping);

        return $invoice;
    }
}
