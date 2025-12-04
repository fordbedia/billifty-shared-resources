<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Jobs;

use BilliftySDK\SharedResources\Modules\Invoicing\Mail\InvoiceSendMailToClient;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class SendToClientJob implements ShouldQueue
{
    use Queueable;

	protected int $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(
		public int $invoiceId,
		public string $userMessage,
		?int $userId = null,
	) {
        $this->userId = $userId ?? auth()->id();
    }

    /**
     * Execute the job.
     */
    public function handle(InvoiceContracts $invoices): void
    {
        $invoice = $invoices->findById($this->invoiceId, $this->userId);

		if (!$invoice) {
            throw new RuntimeException("Invoice {$this->invoiceId} not found for email sending.");
        }

        if (!$invoice->pdf_path) {
            throw new RuntimeException("Invoice {$this->invoiceId} has no pdf_path set. PDF must be generated first.");
        }

		$toEmail = $invoice->client?->email;

		if (!$toEmail) {
			throw new RuntimeException("No Client email address found for invoice {$invoice->invoice_number}.");
		}

		$renderedMessage = $this->renderUserMessage($invoice, $this->userMessage);

		Mail::to($toEmail)->send(
            new InvoiceSendMailToClient($invoice, $renderedMessage)
        );

    }

	protected function renderUserMessage(Invoices $invoice, string $raw): string
    {
        $client          = $invoice->client;
        $businessProfile = $invoice->businessProfile;
        $user            = $invoice->user;

        $invoiceNumber = $invoice->invoice_number
            ?? ('invoice-' . $invoice->getKey());

        $contactName = $businessProfile->name
            ?? $user->name
            ?? 'your team';

        $clientName = $client->name ?? 'your client';

        // We escape values so they’re safe inside HTML.
        $replacements = [
            '{{client.name}}'                => e($clientName),
            '{{invoice.number}}'             => e($invoiceNumber),
            '{{businessProfile.name}}' => e($contactName),
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $raw
        );
    }
}
