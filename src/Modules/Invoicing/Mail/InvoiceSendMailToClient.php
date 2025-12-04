<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Mail;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Str;

class InvoiceSendMailToClient extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Invoices $invoice, public string $userMessage)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
		$invoiceNumber =  $this->invoice->invoice_number ?? ('invoice-' . $this->invoice->getKey());
        return new Envelope(
            subject: 'Copy of invoice ' . $invoiceNumber . '',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'invoicing::emails.invoice_send_direct_to_client', // create this Blade
            with: [
                'invoice' => $this->invoice,
				'userMessage' => $this->userMessage
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
		$invoice = $this->invoice;
        $disk    = $invoice->pdf_disk ?? 'public';
        $path    = $invoice->pdf_path;

        if (!$path) {
            return []; // nothing to attach (defensive)
        }

        $number = $invoice->invoice_number ?? ('invoice-' . $invoice->getKey());
        $client = $invoice->client->name ?? null;
        $base   = $client
            ? Str::slug("{$number}_{$client}", '_')
            : Str::slug($number, '_');
        $filename = "{$base}.pdf";
        return [
            Attachment::fromStorageDisk($disk, $path)
                ->as($filename)
                ->withMime('application/pdf'),
        ];
    }
}
