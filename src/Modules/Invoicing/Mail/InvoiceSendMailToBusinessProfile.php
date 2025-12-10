<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Mail;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class InvoiceSendMailToBusinessProfile extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invoices $invoice
    ) {}

    /**
     * Envelope: subject, from, reply-to, etc.
     */
    public function envelope(): Envelope
    {
        $number = $this->invoice->invoice_number ?? ('invoice-' . $this->invoice->getKey());

        return new Envelope(
            subject: "Copy of invoice {$number}",
        );
    }

    /**
     * Content: which view to render and with which data.
     */
    public function content(): Content
    {
        return new Content(
            view: 'invoicing::emails.invoice_send_copy_to_business_profile', // create this Blade
            with: [
                'invoice' => $this->invoice,
            ],
        );
    }

    /**
     * Attachments: attach the already-generated PDF from storage.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $invoice = $this->invoice;
        $disk    = $invoice->pdf_disk ?? 'public';
        $path    = $invoice->pdf_path;
		$csvPath = $invoice->csv_path;

        if (!$path) {
            return []; // nothing to attach (defensive)
        }

        $number = $invoice->invoice_number ?? ('invoice-' . $invoice->getKey());
        $client = $invoice->client->name ?? null;
        $base   = $client
            ? Str::slug("{$number}_{$client}", '_')
            : Str::slug($number, '_');
        $filename = "{$base}.pdf";

		$csvFilename = "{$base}.csv";

		$attachments = [];
		$attachments[] = Attachment::fromStorageDisk($disk, $path)
                ->as($filename)
                ->withMime('application/pdf');

		if ($csvPath) {
			$attachments[] = Attachment::fromStorageDisk($disk, $csvPath)
                ->as($csvFilename)
                ->withMime('application/csv');
		}

        return $attachments;
    }
}
