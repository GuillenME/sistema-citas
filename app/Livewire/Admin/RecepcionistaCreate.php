<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class RecepcionistaCreate extends Component
{
    public $nombre, $apellido, $email, $telefono, $password, $password_confirmation;
    public $confirmar = false;

    protected $rules = [
        'nombre' => 'required|string|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ];

    public function abrirConfirmacion()
    {
        $this->validate();
        $this->confirmar = true;
    }

    public function guardar()
    {
        Usuario::create([
            'name' => $this->nombre,
            'last_name' => $this->apellido,
            'email' => $this->email,
            'phone' => $this->telefono,
            'password' => Hash::make($this->password),
            'role_id' => 3,
            'active' => 1,
        ]);

        session()->flash('success', 'Recepcionista creado correctamente');

        return redirect()->route('admin.recepcionistas.index');
    }

    public function render()
    {
        return view('livewire.admin.recepcionista-create');
    }
}
