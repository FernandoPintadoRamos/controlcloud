<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarcajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marcajes', function (Blueprint $table) {
            $table->engine = 'InnoDB';                                      // Esto permite escribir Relaciones y Claves Foráneas
            $table->increments('id');
            $table->unsignedInteger('id_worker');
            $table->foreign('id_worker')->references('id')->on('users');    // Atribuye marcajes a un usuario
            $table->date('entrance');
            $table->time('check_in_time');
            $table->string('entrance_note', 1000)->nullable();
            $table->string('nature_of_work')->nullable();                               // Teletrabajo, presencial...
            $table->date('exit')->nullable();
            $table->time('departure_time')->nullable();
            $table->string('exit_note', 1000)->nullable();
            //Columnas de users
            $table->string('CEN')->length(3);
            $table->integer('EMP')->length(4); 
            $table->string('CIF')->length(9)->nullable(); 
            $table->decimal('totalHoras')->nullable();
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
        Schema::dropIfExists('marcajes');
    }
}
