<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ], false));

        return (new MailMessage)
            ->subject('Recupera tu acceso | Barbería 💈')
            ->greeting('Hola 👋')
            ->line('Recibimos una solicitud para restablecer tu contraseña.')
            ->line('Haz clic en el botón de abajo para continuar:')
            ->action('Restablecer contraseña', $url)
            ->line('Este enlace es válido por 60 minutos.')
            ->line('Si no solicitaste este cambio, ignora este correo.')
            ->salutation('— Equipo Barbería ✂️');
    }
}
