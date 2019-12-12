<?php

use App\Recurso;
use Faker\Generator as Faker;

$factory->define(Recurso::class, function (Faker $faker) {
    return [
        'nombre' => $faker->word()
    ];
});
