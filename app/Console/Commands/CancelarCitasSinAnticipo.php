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
        $citas = Cita::where('status', 'pendiente_anticipo')
            ->whereNull('receipt')
            ->where('created_at', '<=', Carbon::now()->subMinutes(15))
            ->get();

        foreach ($citas as $cita) {
            $this->cancelar($cita, 'No se recibió anticipo en 15 minutos');
        }

        return Command::SUCCESS;
    }


    protected function cancelar(Cita $cita, string $motivo)
    {
        $cita->update([
            'status' => 'cancelada',
            'notes' => $motivo
        ]);
    }
}
