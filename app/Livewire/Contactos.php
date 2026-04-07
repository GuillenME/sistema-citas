<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactoMail;
use Throwable;

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

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'email.required' => 'El correo electronico es obligatorio.',
        'email.email' => 'Ingresa un correo electronico valido.',
        'mensaje.required' => 'El mensaje es obligatorio.',
        'mensaje.min' => 'El mensaje debe tener al menos 5 caracteres.',
    ];

    public function enviar()
    {
        $this->validate();

        try {
            Mail::to(config('mail.from.address'))
                ->send(new ContactoMail([
                    'nombre'   => $this->nombre,
                    'apellido' => $this->apellido,
                    'email'    => $this->email,
                    'asunto'   => $this->asunto,
                    'mensaje'  => $this->mensaje,
                ]));

            $this->reset(['nombre', 'apellido', 'email', 'asunto', 'mensaje']);
            session()->flash('success', 'Mensaje enviado correctamente.');
        } catch (Throwable $e) {
            Log::error('Error al enviar el formulario de contacto.', [
                'email' => $this->email,
                'message' => $e->getMessage(),
            ]);

            session()->flash('error', 'No se pudo enviar el mensaje en este momento. Intenta de nuevo mas tarde.');
        }
    }

    public function render()
    {
        return view('livewire.contactos');
    }
}
