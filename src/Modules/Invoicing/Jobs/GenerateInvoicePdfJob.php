<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Jobs;

use BilliftySDK\SharedResources\Modules\Invoicing\Action\GenerateInvoicePdf;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;

class GenerateInvoicePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected int $userId;

    public function __construct(
        public int $invoiceId,
		?int $userId = null
    ) {
		$this->userId = $userId ?? auth()->id();
	}

    public function handle(InvoiceContracts $invoices, GenerateInvoicePdf $action): void
    {
        $invoice = $invoices->findById($this->invoiceId, $this->userId);

        if (!$invoice) {
            throw new RuntimeException("Invoice {$this->invoiceId} not found for PDF generation.");
        }

        // Mark as processing
        $invoice->forceFill([
            'pdf_status' => 'processing',
            'pdf_error'  => null,
        ])->save();

        try {
            ['invoice' => $invoiceObj, 'path' => $path] = $action($invoice);

            $invoiceObj->forceFill([
                'pdf_status'       => 'ready',
                'pdf_generated_at' => now(),
                'pdf_error'        => null,
            ])->save();
        } catch (\Throwable $e) {
            $invoice->forceFill([
                'pdf_status' => 'failed',
                'pdf_error'  => $e->getMessage(),
            ])->save();

            throw $e;
        }
    }
}
