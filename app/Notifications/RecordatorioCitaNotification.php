<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Cita;

class RecordatorioCitaNotification extends Notification
{
    use Queueable;

    public function __construct(public Cita $cita)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Recordatorio de cita')
            ->line('Tienes una cita programada.')
            ->line('Fecha: '.$this->cita->date->format('d/m/Y'))
            ->line('Hora: '.$this->cita->start_time->format('H:i'))
            ->line('Servicio: '.$this->cita->service->name)
            ->line('Te esperamos.');
    }
}
