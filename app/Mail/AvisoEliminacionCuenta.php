<?php

namespace App\Mail;

use App\Models\Usuario;
use Illuminate\Mail\Mailable;

class AvisoEliminacionCuenta extends Mailable
{
    public $usuario;

    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    public function build()
    {
        return $this->subject('Aviso de Eliminación de Cuenta')
            ->view('emails.aviso-eliminacion');
    }
}
