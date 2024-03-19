<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class resp_master extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario, $pqrs, $remitente;

    public function __construct($usuario, $pqrs, $remitente)
    {
        $this->usuario   = $usuario;
        $this->pqrs      = $pqrs;
        $this->remitente = $remitente;
    }

    public function build(){
      return $this->subject("Coopserp PQRS - ".$this->pqrs)->view('email.resp_master');
    }
}
