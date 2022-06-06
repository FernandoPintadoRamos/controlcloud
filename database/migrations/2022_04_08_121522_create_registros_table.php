<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->integer('horas_previstas');
            $table->integer('horas_registradas');
            $table->integer('bolsa_horas')->default(0);
            $table->integer('horas_compensadas');

            // Relacion con user
            $table->integer('id_worker');
            
            $table->string('CIF')->length(9)->nullable();
            $table->string('fecha_registro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registros');
    }
}
