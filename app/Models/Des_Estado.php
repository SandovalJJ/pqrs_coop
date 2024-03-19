<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Des_Estado extends Model
{
    use HasFactory;

    protected $table = 'des_estado';
    protected $filiable = [
        'id', 
        'descripcion'
    ];
}
