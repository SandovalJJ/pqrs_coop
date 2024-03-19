<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reenviado extends Model
{
    use HasFactory;

    protected $table = 'reenviado';
    protected $filiable = [
        'radicado', 
        'ag_anterior', 
        'ag_nueva', 
        'fecha_traslado'
    ];

    public $timestamps = false;
}
