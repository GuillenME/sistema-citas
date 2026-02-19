<?php

namespace App\Notifications;

use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CitaClienteNotification extends Notification
{
    use Queueable;

    public const CONFIRMADA_CON_EMPLEADO = 'confirmada_con_empleado';
    public const CANCELADA_POR_ADMIN = 'cancelada_por_admin';

    public function __construct(
        public Cita $cita,
        public string $tipo,
        public ?string $motivo = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $servicio = $this->cita->service?->name ?? 'Servicio';
        $fecha = $this->cita->date ? $this->cita->date->format('d/m/Y') : '-';
        $hora = Carbon::parse($this->cita->getRawOriginal('start_time'))->format('h:i A');
        $esConfirmacion = $this->tipo === self::CONFIRMADA_CON_EMPLEADO;

        return (new MailMessage)
            ->subject($esConfirmacion ? 'Tu cita fue confirmada' : 'Tu cita fue cancelada')
            ->view('emails.cita-cliente', [
                'nombre' => $notifiable->name ?? 'cliente',
                'tipo' => $this->tipo,
                'servicio' => $servicio,
                'fecha' => $fecha,
                'hora' => $hora,
                'empleado' => $this->cita->employee?->name ?? 'Por asignar',
                'motivo' => $this->motivo ?: 'No especificado',
            ]);
    }
}
