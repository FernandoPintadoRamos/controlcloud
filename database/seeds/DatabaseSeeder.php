<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Image;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        // $this->call(UsersTableSeeder::class);
        
        DB::table('absences')->insert([
            'type' => 'Vacaciones',
        ]);
        DB::table('absences')->insert([
            'type' => 'Baja médica',
        ]);
        DB::table('absences')->insert([
            'type' => 'Falta',
        ]);

        $actual_date = date('Y-m-d');
        $pass = Hash::make('admin');

        DB::table('users')->insert([
            'COD'       => '1',
            'NOM'       => 'Admin',
            'AP1'       => 'Admin',
            'AP2'       => 'Admin',
            'CEN'       => '001',
            'DNI'       => '0000000X',
            'FAL'       =>  $actual_date,
            'EMP'       => '2',
            'email'     => 'practicas@hknominas.es',
            'password'  => $pass,
            'role'      => 'supervisor',
            'email_verified_at' => '2019-02-13 00:00:00.000000',
            'timetable_entrance' => '09:00:00',
            'timetable_exit' => '14:00:00',
        ]);

        DB::table('descriptivos')->insert([
            'Código' => 'NOMI',
            'Descriptivo' => 'Nomina'
        ]);

        //factory(\App\User::class, 99)->create();
        //factory(\App\Marcajes::class, 20)->create();
        factory(\App\Courtesy::class, 1)->create();
        //factory(\App\Absenteeism::class, 10)->create();
    }
}
