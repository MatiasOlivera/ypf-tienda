<?php

use App\EmpleadoPermiso;
use Faker\Generator as Faker;

$factory->define(EmpleadoPermiso::class, function (Faker $faker) {
    return [
        'ver' => $faker->numberBetween(0, 2),
        'crear' => $faker->numberBetween(0, 2),
        'editar' => $faker->numberBetween(0, 2),
        'borrar' => $faker->numberBetween(0, 2)
    ];
});
