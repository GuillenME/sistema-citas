<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Empleado;
use App\Models\Servicio;

class EmpleadoEdit extends Component
{
    public Empleado $empleado;
    public $nombre, $telefono, $activo;
    public $serviciosSeleccionados = [];

    protected $rules = [
        'nombre' => 'required',
        'telefono' => 'required',
        'serviciosSeleccionados' => 'required|array|min:1|max:3',
        'serviciosSeleccionados.*' => 'exists:services,id',
        'activo' => 'required|boolean'
    ];

    public function mount(Empleado $empleado)
    {
        $this->empleado = $empleado;
        $this->nombre = $empleado->name;
        $this->telefono = $empleado->phone;
        $this->serviciosSeleccionados = $empleado->servicios()->pluck('services.id')->toArray();
        $this->activo = $empleado->active;
    }

    public function updatedServiciosSeleccionados($value)
    {
        if (count($this->serviciosSeleccionados) > 3) {
            $this->serviciosSeleccionados = array_slice($this->serviciosSeleccionados, 0, 3);
        }
    }

    public function actualizar()
    {
        $this->validate();

        $this->empleado->update([
            'name' => $this->nombre,
            'phone' => $this->telefono,
            'active' => $this->activo,
        ]);

        $this->empleado->servicios()->sync($this->serviciosSeleccionados);

        session()->flash('success', 'Empleado actualizado correctamente');

        return redirect()->route('admin.empleados.index');
    }

    public function render()
    {
        return view('livewire.admin.empleado-edit', [
            'servicios' => Servicio::orderBy('name')->get(),
        ]);
    }
}
