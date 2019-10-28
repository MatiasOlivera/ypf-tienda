<?php

use App\EmpleadoCargo;
use Faker\Generator as Faker;

$factory->define(EmpleadoCargo::class, function (Faker $faker) {
    return [
        'nombre' => $faker->word()
    ];
});
