<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class master_conf_auto extends Mailable
{
    use Queueable, SerializesModels;

    public $pqrs, $nombres, $apellidos;

    public function __construct($pqrs, $nombres, $apellidos)
    {
        $this->pqrs      = $pqrs;
        $this->nombres   = $nombres;
        $this->apellidos = $apellidos;
    }

    public function build(){
      return $this->subject("Finalizado PQRS - ".$this->pqrs)->view('email.master_conf_auto');
    }
}
