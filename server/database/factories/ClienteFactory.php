<?php

use Faker\Generator as Faker;

$factory->define(App\Cliente::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name(),
        'documento' => $faker->randomNumber(8, true),
        'observacion' => null
    ];
});
