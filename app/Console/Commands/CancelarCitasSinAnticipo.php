<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;
use Carbon\Carbon;

class CancelarCitasSinAnticipo extends Command
{
    protected $signature = 'citas:cancelar-sin-anticipo';
    protected $description = 'Cancela citas sin depósito según reglas de tiempo';

    public function handle()
    {
        $ahora = Carbon::now();

        $citas = Cita::where('estado', 'pendiente_anticipo')
            ->whereNull('comprobante')
            ->get();

        foreach ($citas as $cita) {

            $fechaCita = Carbon::parse($cita->fecha);
            $creada = Carbon::parse($cita->created_at);

            // 🔴 CASO 1: cita a 1 o 2 días → solo 6 horas
            if ($fechaCita->diffInDays($ahora) <= 2) {

                if ($creada->diffInHours($ahora) >= 6) {
                    $this->cancelar($cita, 'No se recibió anticipo en 6 horas');
                }

            }
            // 🔴 CASO 2: cita normal → 3 días
            else {
                if ($creada->diffInDays($ahora) >= 3) {
                    $this->cancelar($cita, 'No se recibió anticipo en 3 días');
                }
            }
        }

        return Command::SUCCESS;
    }

    protected function cancelar(Cita $cita, string $motivo)
    {
        $cita->update([
            'estado' => 'cancelada',
            'observaciones' => $motivo
        ]);
    }
}
