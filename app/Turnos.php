<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turnos extends Model
{
    
    protected $fillable = [
        // Lunes
        'LMD',    //Lunes Mañana Desde
        'LMH',
        'LTD',    
        'LTH',
        'LND',
        'LNH',
        'LED',
        'LEH',
        'LCD',
        'LCH',

        // Martes
        'MMD',    //Martes Mañana Desde
        'MMH',
        'MTD',
        'MTH',
        'MND',
        'MNH',
        'MED',
        'MEH',
        'MCD',
        'MCH',

        // Miercoles
        'XMD',    //Miercoles Mañana Desde
        'XMH',
        'XTD',
        'XTH',
        'XND',
        'XNH',
        'XED',
        'XEH',
        'XCD',
        'XCH',

        // Jueves
        'JMD',    //Jueves Mañana Desde
        'JMH',
        'JTD',
        'JTH',
        'JND',
        'JNH',
        'JED',
        'JEH',
        'JCD',
        'JCH',

        // Viernes
        'VMD',    //Viernes Mañana Desde
        'VMH',
        'VTD',
        'VTH',
        'VND',
        'VNH',
        'VED',
        'VEH',
        'VCD',
        'VCH',

        // Sabado
        'SMD',
        'SMH',
        'STD',
        'STH',
        'SND',
        'SNH',
        'SED',
        'SEH',
        'SCD',
        'SCH',
        
        // Domingo
        'DMD',
        'DMH',
        'DTD',
        'DTH',
        'DND',
        'DNH',
        'DED',
        'DEH',
        'DCD',
        'DCH',
    ];
}
