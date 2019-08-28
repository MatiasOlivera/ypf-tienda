<?php

use App\Observacion;
use Faker\Generator as Faker;

$factory->define(Observacion::class, function (Faker $faker) {
    return [
        'descripcion' => $faker->sentence()
    ];
});
