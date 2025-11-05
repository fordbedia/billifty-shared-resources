<?php
/**
 * @Author: Ed Bedia
 *
 * Usage:
 * $invoice->load('items');
 * app(InvoiceCalculator::class)->compute($invoice);
 * $invoice->push(); // saves invoice + items
 */

namespace BilliftySDK\SharedResources\Modules\Invoicing\Services;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;

final class InvoiceCalculator
{
    public function compute(Invoices $invoice): Invoices
    {
        // Helpers
        $toInt   = static fn($v) => (int) (is_numeric($v) ? $v : 0);
        $toFloat = static fn($v) => (float) (is_numeric($v) ? $v : 0.0);

        $subtotalBase = 0;   // sum of line bases (qty * unit) AFTER line discounts, BEFORE any tax
        $taxSum       = 0;   // sum of per-item taxes (in cents)

        // Guard if items relation isn’t loaded
        $items = $invoice->items ?? collect();

        foreach ($items as $idx => $it) {
            $qty      = $toFloat($it->quantity ?? 0);          // can be decimal
            $unit     = $toInt($it->unit_price_cents ?? 0);    // cents
            $taxRate  = $toFloat($it->tax_rate ?? 0);          // percent, e.g. 8.25
            $ldCents  = $it->line_discount_cents ?? null;      // optional cents
            $ldRate   = $it->line_discount_rate ?? null;       // optional rate (0.10 = 10%)

            // Base (pre-tax, post-discount) at cent precision
            $base = (int) round($qty * $unit);

            // Apply per-line discount (prefer explicit cents over rate if both present)
            if ($ldCents !== null && is_numeric($ldCents)) {
                $base -= $toInt($ldCents);
            } elseif ($ldRate !== null && is_numeric($ldRate)) {
                $base -= (int) round($base * $toFloat($ldRate));
            }

            $base = max(0, $base);

            // Per-item tax (Pattern A + item tax)
            $lineTax = (int) round($base * ($taxRate / 100.0));
            $lineWithTax = $base + $lineTax;

            // Accumulate
            $subtotalBase += $base;
            $taxSum       += max(0, $lineTax);

            // Persist back into the item (keep for PDF/UI/debug)
            $it->position          = $it->position ?? ($idx + 1);
            $it->tax_cents         = max(0, $lineTax);
            $it->line_total_cents  = max(0, $lineWithTax);
        }

        // Invoice-level fields
        $discount = $toInt($invoice->discount_cents ?? 0);
        $shipping = $toInt($invoice->shipping_cents ?? 0);

        // Shipping tax
        $shippingTaxRate = $toFloat($invoice->shipping_tax_rate ?? 0); // percent
        $shippingTax     = (int) round($shipping * ($shippingTaxRate / 100.0));

        // If you ALSO support an invoice-level tax override (e.g., tax_rate_bps),
        // you can switch from per-item tax to invoice-level tax here:
        //
        // Example (uncomment to enable override):
        // $invoiceLevelTax = null;
        // if (isset($invoice->tax_rate_bps) && is_numeric($invoice->tax_rate_bps)) {
        //     // basis points: 100 bps = 1%
        //     $bps = $toInt($invoice->tax_rate_bps);
        //     $invoiceLevelTax = (int) round($subtotalBase * ($bps / 10000.0));
        // }
        //
        // $taxTotal = ($invoiceLevelTax !== null) ? max(0, $invoiceLevelTax) : max(0, $taxSum);

        // For now, trust per-item tax
        $taxTotal = max(0, $taxSum);

        // Grand total
        $total = max(0, $subtotalBase - $discount + $taxTotal + $shipping + max(0, $shippingTax));

        // Write back to invoice
        $invoice->subtotal_cents      = $subtotalBase;              // pre-tax, after line discounts
        $invoice->tax_cents           = $taxTotal;                  // items tax total
        $invoice->shipping_tax_cents  = max(0, $shippingTax);       // computed shipping tax
        $invoice->total_cents         = $total;
        if (is_numeric($invoice->amount_due_cents ?? null)) {
            $invoice->amount_due_cents = $total; // or total - payments_total_cents
        }

        return $invoice;
    }
}
