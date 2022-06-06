<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsenteeismsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absenteeisms', function (Blueprint $table) {
            $table->engine = 'InnoDB';                                      // Esto permite escribir Relaciones y Claves Foráneas
            $table->increments('id');
            $table->unsignedInteger('id_worker');
            $table->foreign('id_worker')->references('id')->on('users');    // Atribuye faltas a un usuario
            $table->unsignedInteger('id_absence');                          
            $table->foreign('id_absence')->references('id')->on('absences'); // Tipo de absentismo
            $table->boolean('justify')->default(false);                     // Falta justificada o no. Por defecto, no. (Usuario Supervisor)
            $table->date('withdrawal_date');                                // Fecha de baja
            $table->date('discharge_date');                                 // Fecha de alta
            $table->integer('absenteeism_days')->default(0);                            // Sumará los días al empleado
            $table->integer('holidays_days')->default(0);                               // Restará días de vacaciones al empleado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absenteeisms');
    }
}
