<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Marcajes;
use Faker\Generator as Faker;

$factory->define(Marcajes::class, function (Faker $faker) {
    return [
        'id_worker'       => \App\User::all()->except(5)->random()->id,
        'entrance'        => $faker->date,
        'check_in_time'   => $faker->time,
        'entrance_note'   => $faker->paragraph,
        'nature_of_work'  => $faker->randomElement(['Teletrabajo', 'Presencial']),
        'exit'            => $faker->date,
        'departure_time'  => $faker->time,
        'exit_note'       => $faker->paragraph,
    ];
});
