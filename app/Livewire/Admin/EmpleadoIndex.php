<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Empleado;

class EmpleadoIndex extends Component
{
    public function toggle($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update([
            'active' => !$empleado->active
        ]);
    }

    public function render()
    {
        return view('livewire.admin.empleado-index', [
            'empleados' => Empleado::orderBy('name')->get()
        ]);
    }
}
