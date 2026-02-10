<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Servicio;

class ServicioCreate extends Component
{
    use WithFileUploads;

    public $nombre;
    public $descripcion;
    public $duracion_minutos;
    public $precio;
    public $image;

    public $confirmar = false;

    protected $rules = [
        'nombre' => 'required|min:3',
        'duracion_minutos' => 'required|integer|min:5',
        'precio' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:2048',
    ];

    // 👇 ESTE MÉTODO DEBE EXISTIR Y SER PUBLIC
    public function abrirConfirmacion()
    {
        $this->validate();
        $this->confirmar = true;
    }

    public function guardar()
    {
        $path = $this->image
            ? $this->image->store('servicios', 'public')
            : null;

        Servicio::create([
            'name' => $this->nombre,
            'description' => $this->descripcion,
            'duration_minutes' => $this->duracion_minutos,
            'price' => $this->precio,
            'image' => $path,
            'active' => 1,
        ]);

        session()->flash('success', 'Servicio creado correctamente');

        return redirect()->route('admin.servicios.index');
    }

    public function render()
    {
        return view('livewire.admin.servicio-create');
    }
}
