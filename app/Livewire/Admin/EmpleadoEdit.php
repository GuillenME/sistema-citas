<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Empleado;
use App\Models\Servicio;

class EmpleadoEdit extends Component
{
    public Empleado $empleado;
    public $nombre, $telefono;
    public $serviciosSeleccionados = [];

    protected $rules = [
        'nombre' => 'required',
        'telefono' => 'required|digits:10',
        'serviciosSeleccionados' => 'required|array|min:1|max:5',
        'serviciosSeleccionados.*' => 'exists:services,id',
    ];

    protected $messages = [
        'telefono.required' => 'El telefono es obligatorio.',
        'telefono.digits' => 'El telefono debe tener exactamente 10 digitos numericos.',
    ];

    public function mount(Empleado $empleado)
    {
        $this->empleado = $empleado;
        $this->nombre = $empleado->name;
        $this->telefono = $empleado->phone;
        $this->serviciosSeleccionados = $empleado->servicios()->pluck('services.id')->toArray();
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

    public function updatedTelefono($value): void
    {
        $this->telefono = substr(preg_replace('/\D/', '', (string) $value), 0, 10);
    }

    public function actualizar()
    {
        $this->validate();

        $this->empleado->update([
            'name' => $this->nombre,
            'phone' => $this->telefono,
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
