<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('COD')->length(5);                           // Código
            $table->string('NOM')->length(20);
            $table->string('AP1')->length(20);
            $table->string('AP2')->length(20);


            // Centros posibles
            $table->string('CEN')->length(3);
            $table->string('CEN_02')->length(3)->nullable();
            $table->string('CEN_03')->length(3)->nullable();
            $table->string('CEN_04')->length(3)->nullable();
            $table->string('CEN_05')->length(3)->nullable();
            // Fin centros posibles

            //Posible horario personal
            $table->integer('horario1')->nullable(); 
            $table->integer('horario2')->nullable();
            $table->integer('horario3')->nullable();
            $table->integer('horario4')->nullable();
            $table->integer('horario5')->nullable();           
            //Fin posible horario personal

            $table->string('DNI')->length(9);
            $table->date('FAL')->length(8);
            $table->date('FBA')->length(8)->nullable();
            $table->string('EMP')->length(4);     
            $table->string('CIF')->length(9)->nullable();  
            $table->string('NOM_EMP')->nullable();                        
            $table->string('email'); 
            $table->string('password');  

            $table->string('session_id')->nullable()->default(null)->comment('Almacena el id de la sesión del usuario');
            $table->timestamp('email_verified_at')->nullable();
            
            $table->string('role')->default('empleado');        // Empleado/Supervisor
            $table->integer('absenteeism')->default(0);         // Absentismo laboral. Controlará en cierto modo las bajas médicas o las faltas injustificadas. Sumará cuando tenga absentismo.
            $table->integer('days_holidays')->default(30);      // Días de vacaciones. Por defecto: 30. Se irá restando a medida que se disfruten.
            $table->boolean('on_holidays')->default(false);     // Marca para saber si está o no de vacaciones. Útil en Control Horario.
            $table->boolean('active')->default(true);           // Marca para saber el estado del usuario, si está de alta o baja.
            $table->boolean('available')->default(true);        // Controla si está disponible o de baja médica. Por defecto, disponible.
            $table->boolean('is_logged')->default(false);       // Evita múltiples inicios de sesión con la misma cuenta.
            $table->time('timetable_entrance')->nullable();
            $table->time('timetable_exit')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
