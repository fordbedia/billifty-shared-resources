<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Services;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;

final class InvoiceCalculator
{
    public function compute(Invoices $invoice): Invoices
    {
        $subtotal = 0;

        foreach ($invoice->items as $it) {
            // quantity can be decimal; prices are in cents
            $qty   = (float) ($it->quantity ?? 0);
            $price = (int)   ($it->unit_price_cents ?? 0);

            // Optional: per-line discount in cents or rate (0–1) if you support it
            $line = (int) round($qty * $price, 0);

            if (isset($it->line_discount_cents)) {
                $line -= (int) $it->line_discount_cents;
            } elseif (isset($it->line_discount_rate)) {
                $line -= (int) round($line * (float) $it->line_discount_rate, 0);
            }

            $line = max(0, $line);
            $it->line_total_cents = $line;
            $subtotal += $line;
        }

        $discount = (int) ($invoice->discount_cents ?? 0);
        $shipping = (int) ($invoice->shipping_cents ?? 0);

        // If tax_cents not provided, compute from tax_rate_bps (basis points)
        $tax = (int) ($invoice->tax_cents ?? 0);
        if (!isset($invoice->tax_cents) && isset($invoice->tax_rate_bps)) {
            $tax = (int) round($subtotal * (int)$invoice->tax_rate_bps / 10000);
        }

        $invoice->subtotal_cents = max(0, $subtotal);
        $invoice->tax_cents      = max(0, $tax);
        $invoice->total_cents    = max(0, $subtotal - $discount + $tax + $shipping);

        return $invoice;
    }
}
