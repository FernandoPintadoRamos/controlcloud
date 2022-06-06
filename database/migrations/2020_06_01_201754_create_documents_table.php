<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('id_worker');
            $table->string('tipo');
            $table->foreign('id_worker')->references('id')->on('users')->onDelete('cascade');
            $table->string('doc')->nullable();
            $table->string('description');
            $table->double('size', 8, 2);
            $table->integer('oculto')->default(0);
            $table->string('CIF')->length(9)->nullable();
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
        Schema::dropIfExists('documents');
    }
}
