<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PagoRechazadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cita;

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    public function build()
    {
        return $this->subject('Tu anticipo fue rechazado')
                    ->view('emails.pago-rechazado');
    }
}