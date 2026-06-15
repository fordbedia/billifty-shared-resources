<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Mail;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoicePaymentReminder;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoicePaymentReminderMail extends Mailable
{
	use Queueable, SerializesModels;

	public function __construct(
		public Invoices $invoice,
		public InvoicePaymentReminder $reminder,
		public ?string $publicInvoiceUrl = null,
	) {}

	public function envelope(): Envelope
	{
		$business = $this->invoice->businessProfile ?? null;
		$invoiceNumber = $this->invoice->invoice_number ?? ('invoice-' . $this->invoice->getKey());

		$fromName = $business?->name
			? "{$business->name} via Billifty"
			: 'Billifty Invoices';

		$replyTo = [];
		if (! empty($business?->email)) {
			$replyTo[] = new Address($business->email, $business->name ?? null);
		}

		return new Envelope(
			from: new Address(config('mail.from.address', 'invoices@billifty.com'), $fromName),
			replyTo: $replyTo,
			subject: $this->subjectForOffset($invoiceNumber, (int) $this->reminder->offset_days),
		);
	}

	public function content(): Content
	{
		return new Content(
			view: 'invoicing::emails.invoice_payment_reminder',
			with: [
				'invoice' => $this->invoice,
				'reminder' => $this->reminder,
				'publicInvoiceUrl' => $this->publicInvoiceUrl,
			],
		);
	}

	protected function subjectForOffset(string $invoiceNumber, int $offsetDays): string
	{
		if ($offsetDays < 0) {
			return "Reminder: Invoice {$invoiceNumber} is due soon";
		}

		if ($offsetDays === 0) {
			return "Reminder: Invoice {$invoiceNumber} is due today";
		}

		return "Reminder: Invoice {$invoiceNumber} is overdue";
	}
}
