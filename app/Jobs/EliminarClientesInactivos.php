<?php

namespace App\Jobs;

use App\Mail\AvisoEliminacionCuenta;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EliminarClientesInactivos
{
   public function handle()
{
    Usuario::where('rol_id', 2)
        ->where('activo', 0)
        ->where('updated_at', '<=', now()->subMinutes(3)) // para pruebas
        ->whereNull('aviso_enviado') // o = 0 si usas boolean
        ->each(function ($usuario) {

            Log::info('Enviando aviso de eliminación', [
                'email' => $usuario->email,
            ]);

            Mail::to($usuario->email)
                ->send(new AvisoEliminacionCuenta($usuario));

            $usuario->update([
                'aviso_enviado' => 1
            ]);
        });
}
}