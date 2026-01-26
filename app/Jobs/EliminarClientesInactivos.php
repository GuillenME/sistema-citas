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
    Usuario::where('role_id', 2)
        ->where('active', 0)
        ->where('updated_at', '<=', now()->subMinutes(3)) // para pruebas
        ->whereNull('notice_sent') // o = 0 si usas boolean
        ->each(function ($usuario) {

            Log::info('Enviando aviso de eliminación', [
                'email' => $usuario->email,
            ]);

            Mail::to($usuario->email)
                ->send(new AvisoEliminacionCuenta($usuario));

            $usuario->update([
                'notice_sent' => 1
            ]);
        });
}
}