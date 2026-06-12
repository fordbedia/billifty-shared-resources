<?php

namespace BilliftySDK\SharedResources\Modules\User\Mail;

use BilliftySDK\SharedResources\Modules\User\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage,
        public string $recipient = 'user'
    ) {}

    public function envelope(): Envelope
    {
        $from = new Address(
            address: config('mail.from.address', 'support@billifty.com'),
            name: config('mail.from.name', config('app.name', 'Billifty'))
        );

        $replyTo = [];
        if ($this->recipient === 'admin' && !empty($this->contactMessage->email)) {
            $replyTo[] = new Address(
                address: $this->contactMessage->email,
                name: $this->contactMessage->name ?: null
            );
        }

        return new Envelope(
            from: $from,
            replyTo: $replyTo,
            subject: $this->subjectLine(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: $this->recipient === 'admin'
                ? 'user::emails.contact_message_admin'
                : 'user::emails.contact_message_user',
            with: [
                'contactMessage' => $this->contactMessage,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }

    private function subjectLine(): string
    {
        if ($this->recipient === 'admin') {
            return 'New contact message: ' . $this->contactMessage->subject;
        }

        return 'We received your message: ' . $this->contactMessage->subject;
    }
}
