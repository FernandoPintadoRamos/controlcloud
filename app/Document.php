<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use App\User;

class Document extends Model
{
    protected $fillable = [
        'id_worker', 'doc', 'description', 'size', 'tipo', 'CIF', 'oculto'
    ];

    protected static function boot()
    { 
        parent::boot();
        static::deleting(function($document)
        {   // Elimina de la carpeta storage/app/documents los documentos que estén almacenados.
            if( ! App::runningInConsole() ){
                $doc=$document->doc;    // Guardo en variable porque el parámetro no puede ser eliminado. Entra en bucle.
                Storage::delete('documents/' . $doc); 
            } 
        });
    }

    public function propietarios() {
        return $this->hasMany(User::class, 'id');
    }
}
