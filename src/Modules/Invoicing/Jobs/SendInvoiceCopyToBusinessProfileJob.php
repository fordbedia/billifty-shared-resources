<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Jobs;

use BilliftySDK\SharedResources\Modules\Invoicing\Mail\InvoiceSendMailToBusinessProfile;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class SendInvoiceCopyToBusinessProfileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected int $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $invoiceId, ?int $userId = null)
    {
		$this->userId = $userId ?? auth()->id();
	}

    /**
     * Execute the job.
     */
    public function handle(InvoiceContracts $invoices): void
    {
        // No Auth here, same as your GenerateInvoicePdfJob
        $invoice = $invoices->findById($this->invoiceId, $this->userId);

        if (!$invoice) {
            throw new RuntimeException("Invoice {$this->invoiceId} not found for email sending.");
        }

        if (!$invoice->pdf_path) {
            throw new RuntimeException("Invoice {$this->invoiceId} has no pdf_path set. PDF must be generated first.");
        }

        // Choose which email to send to (business profile / user)
        $toEmail = $invoice->businessProfile?->email
            ?? $invoice->user?->email
            ?? null;

        if (!$toEmail) {
            throw new RuntimeException("No email address found for invoice {$this->invoiceId}.");
        }

        // We're already in a queue worker, so send() is fine here
        Mail::to($toEmail)->send(
            new InvoiceSendMailToBusinessProfile($invoice)
        );
    }
}
