<?php

namespace App\Observers;

use App\Models\Cita;
use App\Models\Usuario;
use App\Notifications\NuevaCitaAdminNotification;

class CitaObserver
{
    public function created(Cita $cita): void
    {
        // buscar administradores
        $admins = Usuario::where('role_id', 1)->get();

        foreach ($admins as $admin) {
            $admin->notify(new NuevaCitaAdminNotification($cita));
        }
    }
}
