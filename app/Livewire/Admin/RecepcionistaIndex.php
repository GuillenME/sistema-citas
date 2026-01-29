<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Usuario;

class RecepcionistaIndex extends Component
{
    public function toggleActivo(Usuario $usuario)
    {
        abort_if($usuario->role_id !== 3, 403);

        $usuario->update([
            'active' => ! $usuario->active
        ]);
    }

    public function render()
    {
        return view('livewire.admin.recepcionista-index', [
            'recepcionistas' => Usuario::where('role_id', 3)->get()
        ]);
    }
}
