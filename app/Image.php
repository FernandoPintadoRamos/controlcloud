<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'id_worker', 'img'];

    public function usuarios() {
        return $this->hasMany(Users::class, 'id');
    }
}
