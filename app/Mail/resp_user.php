<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class resp_user extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario, $pqrs;

    public function __construct($usuario, $pqrs)
    {
        $this->usuario = $usuario;
        $this->pqrs    = $pqrs;
    }

    public function build(){
      return $this->subject("Coopserp PQRS - ".$this->pqrs)->view('email.resp_user');
    }
}
