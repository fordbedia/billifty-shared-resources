<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Jobs;

use BilliftySDK\SharedResources\Modules\Invoicing\Action\GenerateInvoicePdf;
use BilliftySDK\SharedResources\Modules\Invoicing\Action\GenerateInvoiceItemsToCsv;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class GenerateInvoicePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected int $userId;

	/**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

	/**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 3;

	/**
     * Delay between retries (in seconds)
     */
    public function backoff(): array
    {
        return [5, 10, 20, 30, 60];
    }

    public function __construct(
        public int $invoiceId,
		protected bool $hasCsvReport = false,
		?int $userId = null,
    ) {
		$this->userId = $userId ?? auth()->id();
	}

    public function handle(
		InvoiceContracts          $invoiceRepo,
		GenerateInvoicePdf        $action,
		GenerateInvoiceItemsToCsv $generateCsvAction
	): void {
        $invoice = $invoiceRepo->findById($this->invoiceId, $this->userId);

        if (!$invoice) {
            throw new RuntimeException("Invoice {$this->invoiceId} not found for PDF generation.");
        }

        // Mark as processing
        $invoice->forceFill([
            'pdf_status' => 'processing',
            'pdf_error'  => null,
        ])->save();

        try {
			if ($invoice->csv_path) {
				// Deleting the last CSV so storage doesn’t clutter
				Storage::delete($invoice->csv_path);
			}

            ['invoice' => $invoiceObj, 'path' => $path] = $action($invoice);

			['path' => $csvPath] = $generateCsvAction($invoice, $this->userId, $this->hasCsvReport);

            $invoiceObj->forceFill([
                'pdf_status'       => 'ready',
                'pdf_generated_at' => now(),
                'pdf_error'        => null,
				'csv_path'			=> $csvPath
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
