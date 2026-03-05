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
        'apellido' => 'required|string|min:2',
        'email' => 'required|email|unique:users,email',
        'telefono' => 'required|digits:10',
        'password' => 'required|min:6|confirmed',
    ];

    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'email' => 'El campo :attribute debe ser un correo electrónico válido.',
        'unique' => 'Este :attribute ya está registrado.',
        'min' => 'El campo :attribute debe tener al menos :min caracteres.',
        'max' => 'El campo :attribute no debe superar :max caracteres.',
        'confirmed' => 'La confirmación de :attribute no coincide.',
        'telefono.digits' => 'El campo :attribute debe tener exactamente 10 digitos numericos.',
    ];

    protected $validationAttributes = [
        'nombre' => 'nombre',
        'apellido' => 'apellido',
        'email' => 'correo electrónico',
        'telefono' => 'teléfono',
        'password' => 'contraseña',
        'password_confirmation' => 'confirmación de contraseña',
    ];

    public function abrirConfirmacion()
    {
        $this->validate();
        $this->confirmar = true;
    }

    public function updatedTelefono($value): void
    {
        $this->telefono = substr(preg_replace('/\D/', '', (string) $value), 0, 10);
    }

    public function guardar()
    {
        $this->validate();

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
