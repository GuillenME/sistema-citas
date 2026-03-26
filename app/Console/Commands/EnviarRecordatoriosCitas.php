<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;
use Carbon\Carbon;
use App\Notifications\RecordatorioCitaNotification;

class EnviarRecordatoriosCitas extends Command
{
    // Nombre del comando que se ejecuta en consola.
    protected $signature = 'citas:recordatorios';
    // Descripción del comando.
    protected $description = 'Enviar recordatorios de citas próximas';

    public function handle()
    {
        // Obtener la fecha y hora actual, y la fecha y hora dentro de 30 minutos.
        $ahora = Carbon::now();
        $en30min = Carbon::now()->addMinutes(30);

        // Obtener las citas programadas para hoy que comienzan entre ahora y los próximos 30 minutos, con la información del cliente y el servicio.
        $citas = Cita::whereDate('date', today())
            ->whereBetween('start_time', [
                $ahora->format('H:i'),
                $en30min->format('H:i')
            ])
            ->with('client.user','service')
            ->get();

        foreach ($citas as $cita) {

            $usuario = $cita->client->user ?? null;

            if($usuario){
                $usuario->notify(new RecordatorioCitaNotification($cita));
            }
        }

        $this->info('Recordatorios enviados');

    }
}
