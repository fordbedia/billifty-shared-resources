<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Mail;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Str;

class InvoiceSendMailToClient extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invoices $invoice,
        public string   $userMessage,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $invoiceNumber = $this->invoice->invoice_number ?? ('invoice-' . $this->invoice->getKey());

        // Try to pull business profile (adjust relation name if needed)
        $business = $this->invoice->businessProfile ?? $this->invoice->business_profile ?? null;

        // FROM: use your domain (for deliverability)
        $fromName = $business?->name
            ? "{$business->name} via Billifty"
            : 'Billifty Invoices';

        $from = new Address(
            address: config('mail.from.address', 'invoices@billifty.com'),
            name: $fromName
        );

        $replyTo = [];
        if (!empty($business?->email)) {
            $replyTo[] = new Address(
                address: $business->email,
                name: $business->name ?? null
            );
        }

        return new Envelope(
            from: $from,
            replyTo: $replyTo,
            subject: 'Copy of invoice ' . $invoiceNumber,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'invoicing::emails.invoice_send_direct_to_client',
            with: [
                'invoice'     => $this->invoice,
                'userMessage' => $this->userMessage,
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
        $client = $invoice->client?->name ?? null;

        $base = $client
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
