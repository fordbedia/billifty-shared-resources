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

    public function __construct(
        public int $invoiceId
    ) {}

    public function handle(InvoiceContracts $invoices, GenerateInvoicePdf $action): void
    {
        // Important: repo method should NOT rely on Auth here
        $invoice = $invoices->findByKey($this->invoiceId);

        if (!$invoice) {
            throw new RuntimeException("Invoice {$this->invoiceId} not found for PDF generation.");
        }

        $action($invoice);
    }
}