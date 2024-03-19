<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class pqrs_user extends Mailable
{
    use Queueable, SerializesModels;

    public $info, $radicado, $nombres, $apellidos, $nomina, $mensaje;

    public function __construct($radicado, $nombres, $apellidos)
    {
        $this->radicado  = $radicado;
        $this->nombres   = $nombres;
        $this->apellidos = $apellidos;
    }

    public function build(){
      return $this->subject("Coopserp PQRS - ".$this->radicado)->view('email.pqrs_user');
    }
}
