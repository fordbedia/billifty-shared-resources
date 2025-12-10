<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Action;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateInvoiceItemsToCsv
{
	/**
	 * Generate a CSV file for a single invoice:
	 * - Header section (invoice-level data)
	 * - Blank row
	 * - Line items table
	 *
	 * @param Invoices $invoice
	 * @param int $userId
	 * @param bool $hasCsvReport
	 * @return array{path: string|null}  Storage path to the CSV file or null if no items
	 */
    public function __invoke(Invoices $invoice, int $userId, bool $hasCsvReport): array
    {
		if (! $hasCsvReport) {
			// If user didn't request a CSV, bail out early
			return ['path' => null];
		}

        // Make sure we have all needed relations loaded
        $invoice->loadMissing(['businessProfile', 'client', 'items']);

        if (! $invoice->items || $invoice->items->isEmpty()) {
			// If no items, we can decide to skip export
            return ['path' => null];
        }

        $year      = now()->year;
        $month     = now()->month;
        $shortUuid = substr(str_replace('-', '', (string) Str::uuid()), 0, 8);

        // Define filename & path
        $directory = "csv-invoices/invoices/{$year}/{$month}/{$userId}";
        $filename  = 'invoice-' . ($invoice->invoice_number ?? $invoice->id) . '-' . now()->format('Ymd-His') . '-' . $shortUuid . '.csv';
        $path      = $directory . '/' . $filename;

        Storage::makeDirectory($directory);

        // Open file in storage
        $fullPath = Storage::path($path);
        $handle   = fopen($fullPath, 'w');

        // ─────────────────────────────────────────────
        // 1) INVOICE HEADER SECTION (key/value rows)
        // ─────────────────────────────────────────────

        fputcsv($handle, ['Invoice Number', $invoice->invoice_number]);
        fputcsv($handle, ['Business Name', optional($invoice->businessProfile)->name ?? '']);
        fputcsv($handle, ['Business Email', optional($invoice->businessProfile)->email ?? '']);
        fputcsv($handle, ['Client Name', optional($invoice->client)->name ?? '']);
        fputcsv($handle, ['Client Email', optional($invoice->client)->email ?? '']);
        fputcsv($handle, ['Issue Date', optional($invoice->issued_at)->format('Y-m-d')]);
        fputcsv($handle, ['Due Date', optional($invoice->due_at)->format('Y-m-d')]);
        fputcsv($handle, ['Currency', $invoice->currency?->name ?? 'USD']);
        fputcsv($handle, ['Total Amount', $invoice->total_cents / 100]); // adjust column name

        // Blank row as separator
        fputcsv($handle, []);

        // ─────────────────────────────────────────────
        // 2) LINE ITEMS TABLE (column headers)
        // ─────────────────────────────────────────────

        fputcsv($handle, [
            'Item Name',
            'Description',
            'Quantity',
            'Unit Price',
            'Discount',
            'Tax',
            'Line Total',
        ]);

        foreach ($invoice->items as $item) {
            fputcsv($handle, [
                $item->name,
                $item->description ?? '',
                $item->quantity,
                $this->centsToDecimal($item->unit_price_cents ?? $item->unit_price ?? 0),
                $this->centsToDecimal($item->line_discount_cents ?? 0),
                $this->centsToDecimal($item->tax_cents ?? 0),
                $this->centsToDecimal($item->line_total_cents ?? 0),
            ]);
        }

        fclose($handle);

        return ['path' => $path];
    }

    /**
     * Helper to convert cents (int) to decimal (float|string) if needed.
     */
    protected function centsToDecimal(int|float $value): float
    {
        // If already looks like normal decimal (e.g. 100.50), just return
        if ($value < 1000 && fmod((float) $value, 1.0) !== 0.0) {
            return (float) $value;
        }

        return round($value / 100, 2);
    }
}
