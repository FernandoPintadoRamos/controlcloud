<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'COD' => '1',
        'NOM' => 'Prueba',
        'AP1' => 'Prueba',
        'AP2' => 'Prueba',
        'CEN' => '000',
        'DNI' => '54357906M',
        'FAL' =>  date('Y-m-d'),
        'EMP' => '1',
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => date('Y-m-d'),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'role' => 'supervisor',
        'remember_token' => Str::random(10),
        'timetable_entrance' => '09:00:00',
        'timetable_exit' => '14:00:00',
    ];
});
