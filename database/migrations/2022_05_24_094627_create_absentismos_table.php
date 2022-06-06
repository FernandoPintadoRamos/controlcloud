<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsentismosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absentismos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_worker');
            $table->integer('id_document')->nullable();
            $table->string('tipo');
            $table->string('descripcion')->nullable();
            $table->integer('aceptado')->default(0);
            $table->date('desde');
            $table->date('hasta');
            $table->integer('supervisor');
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
        Schema::dropIfExists('absentismos');
    }
}
