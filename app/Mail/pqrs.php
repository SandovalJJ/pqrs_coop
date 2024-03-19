<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class pqrs extends Mailable
{
    use Queueable, SerializesModels;

    public $info, $radicado, $nombres, $apellidos, $nomina, $mensaje;

    public function __construct($info, $radicado, $nombres, $apellidos, $nomina, $mensaje)
    {
        $this->info      = $info;
        $this->radicado  = $radicado;
        $this->nombres   = $nombres;
        $this->apellidos = $apellidos;
        $this->nomina    = $nomina;
        $this->mensaje   = $mensaje;
    }

    public function build(){
      return $this->subject("Nuevo PQRS - ".$this->radicado)->view('email.pqrs');
    }
}
