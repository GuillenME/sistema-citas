<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Empleado;
use App\Models\Servicio;

class EmpleadoCreate extends Component
{
    public $nombre, $telefono;
    public $serviciosSeleccionados = [];
    public $confirmar = false;

    protected $rules = [
        'nombre' => 'required|min:3',
        'telefono' => 'required',
        'serviciosSeleccionados' => 'required|array|min:1|max:3',
        'serviciosSeleccionados.*' => 'exists:services,id',
    ];

    public function abrirConfirmacion()
    {
        $this->validate();
        $this->confirmar = true;
    }

    public function updatedServiciosSeleccionados($value)
    {
        if (count($this->serviciosSeleccionados) > 3) {
            $this->serviciosSeleccionados = array_slice($this->serviciosSeleccionados, 0, 3);
        }
    }

    public function guardar()
    {
        $empleado = Empleado::create([
            'name' => $this->nombre,
            'phone' => $this->telefono,
            'active' => 1,
        ]);

        $empleado->servicios()->sync($this->serviciosSeleccionados);

        session()->flash('success', 'Empleado creado correctamente');

        return redirect()->route('admin.empleados.index');
    }

    public function render()
    {
        return view('livewire.admin.empleado-create', [
            'servicios' => Servicio::orderBy('name')->get(),
        ]);
    }
}
