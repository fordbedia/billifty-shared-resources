<?php

namespace BilliftySDK\SharedResources\Modules\User\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        $recipientName = $notifiable->name ?? 'there';
        $recipientEmail = method_exists($notifiable, 'getEmailForVerification')
            ? $notifiable->getEmailForVerification()
            : ($notifiable->email ?? null);

        return (new MailMessage)
            ->subject('Verify your Billifty account')
            ->view('user::emails.verify_email', [
                'verificationUrl' => $verificationUrl,
                'recipientName' => $recipientName,
                'recipientEmail' => $recipientEmail,
            ])
            ->text('user::emails.verify_email_text', [
                'verificationUrl' => $verificationUrl,
                'recipientName' => $recipientName,
                'recipientEmail' => $recipientEmail,
            ]);
    }

	protected function verificationUrl(object $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
