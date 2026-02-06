<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactoMail;

class Contactos extends Component
{
    public $nombre;
    public $apellido;
    public $email;
    public $asunto;
    public $mensaje;

    protected $rules = [
        'nombre'   => 'required|string|max:100',
        'apellido' => 'nullable|string|max:100',
        'email'    => 'required|email',
        'asunto'   => 'nullable|string|max:150',
        'mensaje'  => 'required|string|min:5',
    ];

    public function enviar()
    {
        $this->validate();

        Mail::to(config('mail.from.address'))
            ->send(new ContactoMail([
                'nombre'   => $this->nombre,
                'apellido' => $this->apellido,
                'email'    => $this->email,
                'asunto'   => $this->asunto,
                'mensaje'  => $this->mensaje,
            ]));

        // limpiar formulario
        $this->reset();

        session()->flash('success', 'Mensaje enviado correctamente');
    }

    public function render()
    {
        return view('livewire.contactos');
    }
}