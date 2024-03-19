<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class pqrs_fin_user extends Mailable
{
    use Queueable, SerializesModels;

    public $pqrs;

    public function __construct($pqrs)
    {
        $this->pqrs = $pqrs;
    }

    public function build(){
      return $this->subject("Finalizado PQRS - ".$this->pqrs)->view('email.fin_user');
    }
}
