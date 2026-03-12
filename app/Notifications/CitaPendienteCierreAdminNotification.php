<?php

namespace App\Notifications;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CitaPendienteCierreAdminNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Cita $cita)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $cliente = trim((string) (($this->cita->client?->user?->name ?? '') . ' ' . ($this->cita->client?->user?->last_name ?? '')));
        $servicio = (string) ($this->cita->service?->name ?? 'Servicio');
        $fecha = $this->cita->date?->format('d/m/Y') ?? '-';
        $hora = $this->cita->start_time?->format('H:i') ?? '-';

        return (new MailMessage)
            ->subject('Cita pendiente de cierre')
            ->greeting('Hola administrador')
            ->line('Hay una cita confirmada que ya termino y sigue pendiente de cierre.')
            ->line('Cliente: ' . ($cliente !== '' ? $cliente : 'Cliente'))
            ->line('Servicio: ' . $servicio)
            ->line('Fecha: ' . $fecha)
            ->line('Hora: ' . $hora)
            ->action('Revisar citas', url('/admin/citas'))
            ->line('Marca la cita como completada o no asistio segun corresponda.');
    }

    public function toArray($notifiable): array
    {
        $cliente = trim((string) (($this->cita->client?->user?->name ?? '') . ' ' . ($this->cita->client?->user?->last_name ?? '')));
        $servicio = (string) ($this->cita->service?->name ?? 'Servicio');

        return [
            'cita_id' => $this->cita->id,
            'cliente' => $cliente !== '' ? $cliente : 'Cliente',
            'servicio' => $servicio,
            'fecha' => $this->cita->date?->format('d/m/Y'),
            'hora' => $this->cita->start_time?->format('H:i'),
            'mensaje' => 'Hay una cita terminada pendiente de marcar como completada o no asistio.',
        ];
    }
}
