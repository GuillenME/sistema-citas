<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;
use Carbon\Carbon;
use App\Notifications\RecordatorioCitaNotification;

class EnviarRecordatoriosCitas extends Command
{
    protected $signature = 'citas:recordatorios';

    protected $description = 'Enviar recordatorios de citas próximas';

    public function handle()
    {
        $ahora = Carbon::now();
        $en30min = Carbon::now()->addMinutes(30);

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
