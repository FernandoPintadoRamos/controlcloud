<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    //
    protected $fillable = [
        'usuario_receptor', 'usuario_transmisor', 'asunto', 'contenido', 'visto', 'fecha', 'hora', 'id_archivo'
    ];
}
