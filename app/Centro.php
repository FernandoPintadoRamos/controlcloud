<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    //
    protected $fillable = [
        'COD', 'NOM', 'horario', 'cortesia', 'EMP', 'CIF', 'NOM_EMP', 'UBI', 'RAN'
    ];
}
