<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCentrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centros', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->timestamps();

            $table->string('COD')->length(3);
            $table->string('EMP')->length(4);
            $table->string('CIF')->length(9);
            $table->string('NOM_EMP')->nullable();

            $table->string('NOM')->length(25);

            $table->string('UBI')->nullable();
            $table->integer('RAN')->nullable();


            $table->integer('horario')->unsigned();
            $table->foreign('horario')->references('id')->on('turnos');

            $table->integer('cortesia')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('centros');
    }
}
