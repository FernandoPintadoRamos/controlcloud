<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absentismos extends Model
{
    //
    protected $fillable = [
        'id_worker', 'id_document', 'tipo', 'descripcion', 'aceptado', 'desde', 'hasta', 'supervisor'
    ];
}
