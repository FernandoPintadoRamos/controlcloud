<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Absenteeism;
use Faker\Generator as Faker;

$factory->define(Absenteeism::class, function (Faker $faker) {
    return [
        'justify'           => $faker->randomElement([0, 1]),
        'id_worker'         => \App\User::all()->except(5)->random()->id,
        'id_absence'        => $faker->randomElement([1, 2, 3]),
        'withdrawal_date'   => $faker->date,
        'discharge_date'    => $faker->date,
        'absenteeism_days'  => $faker->randomElement([1, 2, 3]),
        'holidays_days'     => $faker->randomElement([1, 2, 3]),
    ];
});
