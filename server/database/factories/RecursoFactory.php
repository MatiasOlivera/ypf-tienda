<?php

use App\Recurso;
use App\Empleado;
use Faker\Generator as Faker;

$factory->define(Recurso::class, function (Faker $faker) {
    return [
        'nombre' => $faker->word()
    ];
});
