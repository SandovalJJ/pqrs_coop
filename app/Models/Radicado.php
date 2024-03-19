<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Radicado extends Model
{
    use HasFactory;

    protected $table = 'radicado';
    protected $filiable = [
        'tipo_solicitud', 
        'tipo_identificacion', 
        'num_identificacion', 
        'nombres', 
        'apellidos', 
        'nomina', 
        'direccion', 
        'correo', 
        'telefono',
        'celular',
        'whatsapp',
        'agencia',
        'mensaje',
        'fecha',
        'hora',
        'estado',
        'conforme',
        'fecha_conforme' 
    ];

    public $timestamps = false;

}
