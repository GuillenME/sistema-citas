<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Empleado;

class EmpleadoCreate extends Component
{
    public $nombre, $telefono, $especialidad;
    public $confirmar = false;

    protected $rules = [
        'nombre' => 'required|min:3',
        'telefono' => 'required',
        'especialidad' => 'required',
    ];

    public function abrirConfirmacion()
    {
        $this->validate();
        $this->confirmar = true;
    }

    public function guardar()
    {
        Empleado::create([
            'name' => $this->nombre,
            'phone' => $this->telefono,
            'specialty' => $this->especialidad,
            'active' => 1,
        ]);

        return redirect()->route('admin.empleados.index');
    }

    public function render()
    {
        return view('livewire.admin.empleado-create');
    }
}
