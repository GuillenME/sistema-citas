<?php

namespace App\Notifications;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NuevaCitaAdminNotification extends Notification
{
    use Queueable;

    public $cita;

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toArray($notifiable)
    {
        return [
            'cita_id' => $this->cita->id,
            'cliente' => $this->cita->client?->name ?? 'Cliente',
            'fecha' => $this->cita->date->format('d/m/Y'),
            'hora' => $this->cita->start_time->format('H:i'),
            'mensaje' => 'Nueva cita agendada',
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nueva cita agendada')
            ->greeting('Hola administrador')
            ->line('Se ha agendado una nueva cita.')
            ->line('Fecha: ' . $this->cita->date->format('d/m/Y'))
            ->line('Hora: ' . $this->cita->start_time->format('H:i'))
            ->action('Ver citas', url('/admin/citas'))
            ->line('Sistema de Barbería');
    }
}
