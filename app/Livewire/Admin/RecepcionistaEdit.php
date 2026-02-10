<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Usuario;

class RecepcionistaEdit extends Component
{
    public Usuario $usuario;

    public $nombre;
    public $apellido;
    public $telefono;

    public $confirmar = false;

    protected $rules = [
        'nombre' => 'required|string|min:3',
    ];

    public function mount(Usuario $usuario)
    {
        abort_if($usuario->role_id !== 3, 404);

        $this->usuario = $usuario;
        $this->nombre = $usuario->name;
        $this->apellido = $usuario->last_name;
        $this->telefono = $usuario->phone;
    }

    public function abrirConfirmacion()
    {
        $this->validate();
        $this->confirmar = true;
    }

    public function actualizar()
    {
        $this->usuario->update([
            'name' => $this->nombre,
            'last_name' => $this->apellido,
            'phone' => $this->telefono,
        ]);

        session()->flash('success', 'Recepcionista actualizado correctamente');

        return redirect()->route('admin.recepcionistas.index');
    }

    public function render()
    {
        return view('livewire.admin.recepcionista-edit');
    }
}
