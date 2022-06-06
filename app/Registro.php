<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    //
    protected $fillable = [
        'horas_previstas', 'horas_registradas', 'bolsa_horas', 'horas_compensadas', 'id_worker', 'fecha_registro', 'CIF'
    ];
}
