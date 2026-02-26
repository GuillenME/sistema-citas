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
        'telefono' => 'required|digits:10',
        'serviciosSeleccionados' => 'required|array|min:1|max:5',
        'serviciosSeleccionados.*' => 'exists:services,id',
    ];

    protected $messages = [
        'telefono.required' => 'El telefono es obligatorio.',
        'telefono.digits' => 'El telefono debe tener exactamente 10 digitos numericos.',
    ];

    public function updatedTelefono($value): void
    {
        $this->telefono = substr(preg_replace('/\D/', '', (string) $value), 0, 10);
    }

    public function abrirConfirmacion()
    {
        $this->validate();
        $this->confirmar = true;
    }

    public function updatedServiciosSeleccionados($value)
    {
        if (count($this->serviciosSeleccionados) > 5) {
            $this->serviciosSeleccionados = array_slice($this->serviciosSeleccionados, 0, 5);
        }
    }

    public function toggleServicio(int $serviceId): void
    {
        $selected = collect($this->serviciosSeleccionados)->map(fn ($id) => (int) $id)->values();

        if ($selected->contains($serviceId)) {
            $this->serviciosSeleccionados = $selected
                ->reject(fn ($id) => $id === $serviceId)
                ->values()
                ->all();
            return;
        }

        if ($selected->count() >= 5) {
            return;
        }

        $this->serviciosSeleccionados = $selected
            ->push($serviceId)
            ->unique()
            ->values()
            ->all();
    }

    public function guardar()
    {
        $this->validate();

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
