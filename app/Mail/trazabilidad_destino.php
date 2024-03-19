<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class trazabilidad_destino extends Mailable
{
    use Queueable, SerializesModels;

    public $pqrs, $age_ant, $age_new;

    public function __construct($pqrs, $age_ant, $age_new)
    {
        $this->pqrs    = $pqrs;
        $this->age_ant = $age_ant;
        $this->age_new = $age_new;
    }

    public function build(){
      return $this->subject("Trazabilidad PQRS - ".$this->pqrs)->view('email.trazabilidad_destino');
    }
}
