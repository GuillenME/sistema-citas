<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;
use Carbon\Carbon;

class CancelarCitasSinAnticipo extends Command
{
    // Nombre del comando que se ejecuta en consola.
    protected $signature = 'citas:cancelar-sin-anticipo';
    // Descripción del comando.
    protected $description = 'Cancela citas sin depósito según reglas de tiempo';

    public function handle()
    {
        // Obtener las citas pendientes de anticipo que no tienen recibo y que fueron creadas hace más de 15 minutos.
        $citas = Cita::where('status', 'pendiente_anticipo')
            ->whereNull('receipt')
            ->where('created_at', '<=', Carbon::now()->subMinutes(15))
            ->get();
        // recorrer las citas y cancelarlas con el motivo correspondiente.
        foreach ($citas as $cita) {
            $this->cancelar($cita, 'No se recibió anticipo en 15 minutos');
        }

        return Command::SUCCESS;
    }

    // función para cancelar una cita con un motivo específico.
    protected function cancelar(Cita $cita, string $motivo)
    {
        $cita->update([
            'status' => 'cancelada', // Cambiar el estado a cancelada
            'notes' => $motivo // enviar el motivo de la cancelación en las notas de la cita
        ]);
    }
}
