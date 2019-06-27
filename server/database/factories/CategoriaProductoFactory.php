<?php

use Faker\Generator as Faker;

$factory->define(App\CategoriaProducto::class, function (Faker $faker) {
    return [
        'descripcion' => $faker->unique()->word()
    ];
});
