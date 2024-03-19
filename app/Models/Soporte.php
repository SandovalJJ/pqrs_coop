<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soporte extends Model
{
    use HasFactory;

    protected $table = 'soporte';
    protected $filiable = [
        'radicado', 
        'respuesta_id',
        'archivo_url', 
        'nombre', 
        'tipo', 
        'fecha'
    ];

    public $timestamps = false;
}
