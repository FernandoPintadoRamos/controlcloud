<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    //
    protected $fillable = [
        'CIF', 'sello', 'usuario_ftp', 'id_supervisor'
    ];
}
