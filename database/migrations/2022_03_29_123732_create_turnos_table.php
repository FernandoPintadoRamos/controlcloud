<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->timestamps();
            // [L, M, X, J, V, S, D] -> Dias de la semana
            // [M, T]                -> Mañana o Tarde
            // [D, H]                -> Desde o Hasta
            

            
            // Lunes   
            $table->time('LMD')->nullable()->deafult(null);    //Lunes Mañana Desde
            $table->time('LMH')->nullable()->deafult(null);
            $table->time('LTD')->nullable()->deafult(null);    
            $table->time('LTH')->nullable()->deafult(null);
            $table->time('LND')->nullable()->deafult(null);
            $table->time('LNH')->nullable()->deafult(null);
            $table->time('LED')->nullable()->deafult(null);
            $table->time('LEH')->nullable()->deafult(null);
            $table->time('LCD')->nullable()->deafult(null);
            $table->time('LCH')->nullable()->deafult(null);

            // Martes
            $table->time('MMD')->nullable()->deafult(null);    //Martes Mañana Desde
            $table->time('MMH')->nullable()->deafult(null);
            $table->time('MTD')->nullable()->deafult(null);
            $table->time('MTH')->nullable()->deafult(null);
            $table->time('MND')->nullable()->deafult(null);
            $table->time('MNH')->nullable()->deafult(null);
            $table->time('MED')->nullable()->deafult(null);
            $table->time('MEH')->nullable()->deafult(null);
            $table->time('MCD')->nullable()->deafult(null);
            $table->time('MCH')->nullable()->deafult(null);

            // Miercoles
            $table->time('XMD')->nullable()->deafult(null);    //Miercoles Mañana Desde
            $table->time('XMH')->nullable()->deafult(null);
            $table->time('XTD')->nullable()->deafult(null);
            $table->time('XTH')->nullable()->deafult(null);
            $table->time('XND')->nullable()->deafult(null);
            $table->time('XNH')->nullable()->deafult(null);
            $table->time('XED')->nullable()->deafult(null);
            $table->time('XEH')->nullable()->deafult(null);
            $table->time('XCD')->nullable()->deafult(null);
            $table->time('XCH')->nullable()->deafult(null);

            // Jueves
            $table->time('JMD')->nullable()->deafult(null);    //Jueves Mañana Desde
            $table->time('JMH')->nullable()->deafult(null);
            $table->time('JTD')->nullable()->deafult(null);
            $table->time('JTH')->nullable()->deafult(null);
            $table->time('JND')->nullable()->deafult(null);
            $table->time('JNH')->nullable()->deafult(null);
            $table->time('JED')->nullable()->deafult(null);
            $table->time('JEH')->nullable()->deafult(null);
            $table->time('JCD')->nullable()->deafult(null);
            $table->time('JCH')->nullable()->deafult(null);

            // Viernes
            $table->time('VMD')->nullable()->deafult(null);    //Viernes Mañana Desde
            $table->time('VMH')->nullable()->deafult(null);
            $table->time('VTD')->nullable()->deafult(null);
            $table->time('VTH')->nullable()->deafult(null);
            $table->time('VND')->nullable()->deafult(null);
            $table->time('VNH')->nullable()->deafult(null);
            $table->time('VED')->nullable()->deafult(null);
            $table->time('VEH')->nullable()->deafult(null);
            $table->time('VCD')->nullable()->deafult(null);
            $table->time('VCH')->nullable()->deafult(null);

            // Sabado
            $table->time('SMD')->nullable()->deafult(null);
            $table->time('SMH')->nullable()->deafult(null);
            $table->time('STD')->nullable()->deafult(null);
            $table->time('STH')->nullable()->deafult(null);
            $table->time('SND')->nullable()->deafult(null);
            $table->time('SNH')->nullable()->deafult(null);
            $table->time('SED')->nullable()->deafult(null);
            $table->time('SEH')->nullable()->deafult(null);
            $table->time('SCD')->nullable()->deafult(null);
            $table->time('SCH')->nullable()->deafult(null);
            
            // Domingo
            $table->time('DMD')->nullable()->deafult(null);
            $table->time('DMH')->nullable()->deafult(null);
            $table->time('DTD')->nullable()->deafult(null);
            $table->time('DTH')->nullable()->deafult(null);
            $table->time('DND')->nullable()->deafult(null);
            $table->time('DNH')->nullable()->deafult(null);
            $table->time('DED')->nullable()->deafult(null);
            $table->time('DEH')->nullable()->deafult(null);
            $table->time('DCD')->nullable()->deafult(null);
            $table->time('DCH')->nullable()->deafult(null);

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turnos');
    }
}
