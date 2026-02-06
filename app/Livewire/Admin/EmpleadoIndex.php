<?php

namespace App\Livewire\Admin;

use App\Models\Empleado;
use Livewire\Component;
use Livewire\WithPagination;

class EmpleadoIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'simple-bootstrap';

    public $confirmDeleteId = null;

    public function toggle($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update([
            'active' => !$empleado->active,
        ]);
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmDeleteId = null;
    }

    public function deleteConfirmed()
    {
        if (!$this->confirmDeleteId) {
            return;
        }

        $empleado = Empleado::findOrFail($this->confirmDeleteId);
        $empleado->delete();
        $this->confirmDeleteId = null;
    }

    public function render()
    {
        return view('livewire.admin.empleado-index', [
            'empleados' => Empleado::orderBy('id')->paginate(5),
        ]);
    }
}
