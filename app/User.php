<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Absenteeism;
use App\Marcajes;
use App\Document;
use App\Notifications\MyResetPassword;
//use App\Notifications\MyVerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'COD', 'NOM','AP1','AP2', 'CEN', 'CEN_02', 'CEN_03', 'CEN_04', 'CEN_05', 'DNI','FAL','FBA','EMP', 'email', 'password', 'role', 'horario1', 'horario2', 'horario3', 'horario4', 'horario5', 'CIF', 'NOM_EMP'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'PWD', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin(){
        if ($this->role=='supervisor') {
            return True;
        } else
            return False;
    }

    public function absenteeism() {
        return $this->belongsTo(Absenteeism::class, 'id_worker');
    }

    public function tieneMarcaje() {
        return $this->hasMany(Marcaje::class, 'id_worker');
    }

    public function tieneDocumentos() {
        return $this->hasMany(Document::class, 'id_worker');
    }

    public function imagenes(){
        return $this->hasMany(Image::class, 'id_worker');
    }

    public function sendPasswordResetNotification($token) {
        $this->notify(new MyResetPassword($token));
    }
    //public function sendEmailVerificationNotification() {
    //    $this->notify(new MyVerifyEmail());
    //}
}
