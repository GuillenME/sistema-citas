<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

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
        return ['mail', 'database']; // email + notificación interna
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🎉 Nueva promoción disponible')
            ->greeting('Hola ' . $notifiable->name)
            ->line('Tenemos una nueva promoción para ti:')
            ->line($this->promocion->titulo)
            ->action('Ver promoción', url('/promociones'))
            ->line('¡Aprovecha antes de que termine!');
    }

    public function toArray($notifiable)
    {
        return [
            'titulo' => $this->promocion->titulo,
            'descripcion' => $this->promocion->descripcion,
        ];
    }
}
