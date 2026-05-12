<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Mail;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessNotificationForBusinessProfileMail extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 */
	public function __construct(
		protected Invoices $invoice,
		protected array    $paymentData
	) {}

	/**
	 * Get the message envelope.
	 */
	public function envelope(): Envelope
	{
		$invoiceNumber = $this->invoice->invoice_number ?? ('invoice-' . $this->invoice->getKey());

		return new Envelope(
			subject: "Payment received for invoice {$invoiceNumber}",
		);
	}

	/**
	 * Get the message content definition.
	 */
	public function content(): Content
	{
		return new Content(
			view: 'billing::emails.invoice-success-payment-to-business-profile',
			with: [
				'invoice' => $this->invoice,
				'paymentData' => $this->paymentData,
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
		return [];
	}
}
