<?php

namespace App\Console\Commands;

use App\Models\Cita;
use App\Models\Usuario;
use App\Notifications\CitaPendienteCierreAdminNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotificarCitasPendientesCierre extends Command
{
    protected $signature = 'citas:notificar-pendientes-cierre';

    protected $description = 'Notifica al admin sobre citas confirmadas que ya terminaron y siguen sin cerrarse';

    public function handle(): int
    {
        $ahora = Carbon::now();

        $citas = Cita::query()
            ->with(['client.user', 'service'])
            ->where('status', 'confirmada')
            ->whereDate('date', '<=', $ahora->toDateString())
            ->get()
            ->filter(function (Cita $cita) use ($ahora) {
                $fechaCita = Carbon::parse($cita->date)->format('Y-m-d');
                $finCita = Carbon::parse($fechaCita . ' ' . $cita->getRawOriginal('end_time'));

                return $ahora->greaterThanOrEqualTo($finCita);
            });

        if ($citas->isEmpty()) {
            $this->info('No hay citas pendientes de cierre.');

            return self::SUCCESS;
        }

        $admins = Usuario::query()->where('role_id', 1)->get();
        $notificacionesEnviadas = 0;

        foreach ($admins as $admin) {
            foreach ($citas as $cita) {
                $yaNotificada = $admin->notifications()
                    ->where('type', CitaPendienteCierreAdminNotification::class)
                    ->where('data->cita_id', $cita->id)
                    ->exists();

                if ($yaNotificada) {
                    continue;
                }

                $admin->notify(new CitaPendienteCierreAdminNotification($cita));
                $notificacionesEnviadas++;
            }
        }

        $this->info("Notificaciones enviadas: {$notificacionesEnviadas}");

        return self::SUCCESS;
    }
}
