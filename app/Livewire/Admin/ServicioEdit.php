<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Servicio;

class ServicioEdit extends Component
{
    use WithFileUploads;

    public Servicio $servicio;

    public $nombre;
    public $descripcion;
    public $duracion_minutos;
    public $precio;
    public $image;
    public $activo;

    public $confirmar = false;

    protected $rules = [
        'nombre' => 'required|min:3',
        'duracion_minutos' => 'required|integer|min:5',
        'precio' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:2048',
        'activo' => 'boolean',
    ];

    public function mount(Servicio $servicio)
    {
        $this->servicio = $servicio;
        $this->nombre = $servicio->name;
        $this->descripcion = $servicio->description;
        $this->duracion_minutos = $servicio->duration_minutes;
        $this->precio = $servicio->price;
        $this->activo = (bool) $servicio->active;
    }

    public function abrirConfirmacion()
    {
        $this->validate();
        $this->confirmar = true;
    }

    public function actualizar()
    {
        if ($this->image) {
            $this->servicio->image = $this->image->store('servicios', 'public');
        }

        $this->servicio->update([
            'name' => $this->nombre,
            'description' => $this->descripcion,
            'duration_minutes' => $this->duracion_minutos,
            'price' => $this->precio,
            'active' => $this->activo ? 1 : 0,
        ]);

        return redirect()->route('admin.servicios.index');
    }

    public function render()
    {
        return view('livewire.admin.servicio-edit');
    }
}
