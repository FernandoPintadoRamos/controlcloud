<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;


class Marcajes extends Model
{

    protected $fillable = [
        'CEN', 'EMP', 'id_worker', 'entrance', 'check_in_time', 'entrance_note', 'nature_of_work', 'exit', 'departure_time', 'exit_note', 'totalHoras', 'CIF'];

    
    public function nameUser(){
        return $this->belongsTo(User::class, 'id_worker');
    }
}
