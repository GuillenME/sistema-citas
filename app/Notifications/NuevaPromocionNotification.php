<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevaPromocionNotification extends Notification
{
    use Queueable;

    public $promocion;

    public function __construct($promocion)
    {
        $this->promocion = $promocion;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nueva promocion disponible')
            ->view('emails.nueva-promocion', [
                'user' => $notifiable,
                'promo' => $this->promocion,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->promocion->title,
            'description' => $this->promocion->description,
        ];
    }
}
