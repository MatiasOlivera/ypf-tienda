<?php

use App\EmpleadoCargo;
use Faker\Generator as Faker;

$factory->define(App\Empleado::class, function (Faker $faker) {
    return [
        'documento' => $faker->randomNumber(8, true),
        'nombre' => $faker->firstname(),
        'apellido' => $faker->lastname(),
        'fecha_nacimiento' => $faker->optional()->date,
        'sexo' => $faker->optional()->randomElement(['F', 'M']),
        'password' => Hash::make('12345678'),

        // Cargo
        'cargo_id' => function () {
            return factory(EmpleadoCargo::class)->create()->id;
        }
    ];
});
