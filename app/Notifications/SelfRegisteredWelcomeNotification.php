<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SelfRegisteredWelcomeNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Bienvenido a Barberia')
            ->view('emails.self-registered-welcome', [
                'nombre' => $notifiable->name ?? 'Cliente',
                'email' => $notifiable->email,
                'loginUrl' => route('login'),
            ]);
    }
}
