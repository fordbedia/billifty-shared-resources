<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Action;

use BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources\InvoiceResource;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateInvoicePdf
{
    /**
     * Generate a PDF for the given invoice and store it.
     *
     * @param  int|Invoices  $invoice
     * @return array{invoice: Invoices, path: string, url: string}
     */
    public function __invoke(int|Invoices $invoice): array
    {
        // 1) Resolve invoice instance
        if (is_int($invoice)) {
            /** @var Invoices $invoice */
            $invoice = Invoices::with(Invoices::relationships())
                ->findOrFail($invoice);
        } else {
            $invoice->loadMissing(Invoices::relationships());
        }

        // 2) Massage color scheme relationship (same as dev route)
        if ($invoice->colorScheme) {
            $invoice->colorScheme->setRelation(
                'colors',
                $invoice->colorScheme->colors->keyBy('name')
            );
        }

        // 3) Build the same payload as your preview route
        //    ->getData(true) returns array instead of stdClass
        $payload = (new InvoiceResource($invoice))
            ->response()
            ->getData();

        // 4) Render the same view you use for preview
        $html = view('invoicing::templates.show', [
            'invoice'     => data_get($payload, 'data'),
            'category'    => data_get($payload, 'data.template.category'),
            'colorScheme' => data_get($payload, 'data.colorScheme'),
			'renderContext' => 'pdf'
        ])->render();

        // 5) Generate PDF from HTML
        /** @var \Barryvdh\DomPDF\PDF $pdf */
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html)->setPaper('A4', 'portrait');
		$pdf->set_option('isRemoteEnabled', true);
        $binary = $pdf->output();

        // 6) Build storage path: invoice_pdfs/{year}/{month}/{invoice#}_{shortUuid}.pdf

        // Prefer invoice issued date for foldering, fallback to now()
        // Normalize issued_on to Carbon or fallback to now()
        $date = $invoice->issued_on
            ? Carbon::parse($invoice->issued_on)
            : now();
        $year  = $date->format('Y');
        $month = $date->format('m');

        // Make the invoice number safe for filenames
        $invoiceNumber = $invoice->invoice_number ?? ('invoice-' . $invoice->getKey());
        $invoiceSafe   = Str::slug($invoiceNumber, '_');

        // Short UUID: strip dashes, keep first 8 chars
        $shortUuid = substr(str_replace('-', '', (string) Str::uuid()), 0, 8);

        $relativePath = "invoice_pdfs/{$year}/{$month}/{$invoiceSafe}_{$shortUuid}.pdf";

        // 7) Store in the public disk (maps to storage/app/public/...)
        $disk = Storage::disk('public');
        $disk->put($relativePath, $binary);

        // 8) (Optional but recommended) – persist where the PDF lives
        $invoice->forceFill([
            'pdf_path' => $relativePath,
            'pdf_disk' => 'public',
        ])->save();

        $url = $disk->url($relativePath);

        return [
            'invoice' => $invoice->refresh(),
            'path'    => $relativePath,
            'url'     => $url,
        ];
    }
}