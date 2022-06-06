<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absenteeism extends Model
{
    protected $fillable = [
        'justify', 'id_worker', 'id_absence', 'withdrawal_date', 'discharge_date', 'absenteeism_days', 'holidays_days'
    ];

    public function absenteeism() {
        return $this->belongsTo(Users::class, 'id_worker');
    }

    public function typeAbsenteeism(){
        return $this->belongsTo(Absence::class, 'id_absence');
    }
}
