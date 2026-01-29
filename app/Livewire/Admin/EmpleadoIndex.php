<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Empleado;
use Livewire\WithPagination;

class EmpleadoIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'simple-bootstrap';
    
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
            'empleados' => Empleado::orderBy('id')->paginate(5)
        ]);
    }
}
