<?php

use App\Provincia;
use Faker\Generator as Faker;

$factory->define(Provincia::class, function (Faker $faker) {
    return [
        'nombre' => $faker->unique()->state()
    ];
});
