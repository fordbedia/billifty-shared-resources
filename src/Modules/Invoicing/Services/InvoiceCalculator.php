<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Services;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;

final class InvoiceCalculator
{
    public function compute(Invoices $invoice): Invoices
    {
        // Helpers – mirror JS helpers
        $toInt   = static fn($v) => (int)(is_numeric($v) ? $v : 0);
        $toFloat = static fn($v) => (float)(is_numeric($v) ? $v : 0.0);

        // Modes: 'none' | 'per-line' | 'amount' | 'percent'
        $mode       = $invoice->discount_mode ?? 'none';
        $usePerLine = ($mode === 'per-line');

        $subtotalBase = 0;   // AFTER per-line discounts (if enabled), BEFORE tax
        $taxSum       = 0;   // sum of item taxes in cents

        // Ensure items are available (you usually call ->load('items') before this)
        $items = collect($invoice->items ?? []);
        $computedItems = collect();

        foreach ($items as $idx => $item) {
            $qty     = $toFloat($item['quantity'] ?? 0);
            $unit    = $toInt($item['unit_price_cents'] ?? 0);
            $taxRate = $toFloat($item['tax_rate'] ?? 0); // percent, e.g. 8.25

            // Base line before tax
            $base = (int) round($qty * $unit);

            // Only apply per-line discounts when mode === 'per-line'
            if ($usePerLine) {
                $ldCentsRaw = $item['line_discount_cents'] ?? null;
                $ldRateRaw  = $item['line_discount_rate'] ?? null;

                $hasCents = is_numeric($ldCentsRaw) && $toInt($ldCentsRaw) > 0;
                $hasRate  = is_numeric($ldRateRaw)  && $toFloat($ldRateRaw) > 0.0;

				if ($hasRate) {
					$pct  = max(0.0, min(100.0, $toFloat($ldRateRaw)));
					$base -= (int) round($base * ($pct / 100.0));
				}
            }

            $base = max(0, $base);

            // Per-item tax (percent)
            $lineTax  = (int) round($base * ($taxRate / 100.0));
            $lineWith = $base + $lineTax;

            // Accumulate
            $subtotalBase += $base;
            $taxSum       += max(0, $lineTax);

            // Write back onto the item so the relation stays in sync for persistence/UI.
            if (is_array($item)) {
                $item['position']         = $item['position'] ?? ($idx + 1);
                $item['tax_cents']        = max(0, $lineTax);
                $item['line_total_cents'] = max(0, $lineWith);
            } else {
                $item->position         = $item->position ?? ($idx + 1);
                $item->tax_cents        = max(0, $lineTax);
                $item->line_total_cents = max(0, $lineWith);
            }

            $computedItems->push($item);
        }

        $invoice->setRelation('items', $computedItems);

        // Invoice-level discount (mutually exclusive with per-line)
        $invoiceLevelDiscount = 0;
        if ($mode === 'amount') {
            $invoiceLevelDiscount = $toInt($invoice->discount_cents ?? 0);
        } elseif ($mode === 'percent') {
            $rate = $toFloat($invoice->discount_rate ?? 0); // 10 => 10%
            $invoiceLevelDiscount = (int) round($subtotalBase * ($rate / 100.0));
        }
        $invoiceLevelDiscount = max(0, $invoiceLevelDiscount);

        // Shipping + shipping tax
        $shipping         = $toInt($invoice->shipping_cents ?? 0);
        $shippingTaxRate  = $toFloat($invoice->shipping_tax_rate ?? 0); // percent
        $shippingTaxCents = (int) round($shipping * ($shippingTaxRate / 100.0));

        // Trust per-item tax (like JS)
        $taxTotal = max(0, $taxSum);

        // Grand total mirrors JS
        $total = max(
            0,
            $subtotalBase - $invoiceLevelDiscount + $taxTotal + $shipping + $shippingTaxCents
        );

        // Write back to invoice
        $invoice->subtotal_cents     = $subtotalBase;                // pre-tax, after per-line discounts
        $invoice->tax_cents          = $taxTotal;                    // items’ tax sum
        $invoice->discount_cents     = $invoiceLevelDiscount;        // normalized invoice-level discount
        $invoice->shipping_tax_cents = max(0, $shippingTaxCents);
        $invoice->total_cents        = $total;

        if (is_numeric($invoice->amount_due_cents ?? null)) {
            $invoice->amount_due_cents = $total; // or subtract payments if you track them
        }

        // Display-only row for UI (negative amount)
        if (($mode === 'amount' || $mode === 'percent') && $invoiceLevelDiscount > 0) {
            $invoice->setAttribute('display_discount_row', [
                'label'        => 'Discount',
                'amount_cents' => -$invoiceLevelDiscount,
            ]);
        } else {
            $invoice->setAttribute('display_discount_row', null);
        }

        return $invoice;
    }
}
