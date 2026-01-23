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
            ->where('created_at', '<=', $ahora->subMinutes(30))
            ->get();

        foreach ($citas as $cita) {
            $this->cancelar($cita, 'No se recibió anticipo en 30 minutos');
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
