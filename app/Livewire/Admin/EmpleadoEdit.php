<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Empleado;

class EmpleadoEdit extends Component
{
    public Empleado $empleado;
    public $nombre, $telefono, $especialidad, $activo;

    protected $rules = [
        'nombre' => 'required',
        'telefono' => 'required',
        'especialidad' => 'required',
        'activo' => 'required|boolean'
    ];

    public function mount(Empleado $empleado)
    {
        $this->empleado = $empleado;
        $this->nombre = $empleado->name;
        $this->telefono = $empleado->phone;
        $this->especialidad = $empleado->specialty;
        $this->activo = $empleado->active;
    }

    public function actualizar()
    {
        $this->validate();

        $this->empleado->update([
            'name' => $this->nombre,
            'phone' => $this->telefono,
            'specialty' => $this->especialidad,
            'active' => $this->activo,
        ]);

        return redirect()->route('admin.empleados.index');
    }

    public function render()
    {
        return view('livewire.admin.empleado-edit');
    }
}
