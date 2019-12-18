<?php

use App\Empleado;
use Faker\Generator as Faker;

$factory->define(Empleado::class, function (Faker $faker) {
    return [
        'documento' => $faker->randomNumber(8, true),
        'nombre' => $faker->firstname(),
        'apellido' => $faker->lastname(),
        'fecha_nacimiento' => $faker->optional()->date,
        'sexo' => $faker->optional()->randomElement(['F', 'M']),
        'password' => Hash::make('12345678')
    ];
});
