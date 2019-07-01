<?php

use Faker\Generator as Faker;

$factory->define(App\Producto::class, function (Faker $faker) {
    return [
        'codigo' => $faker->randomNumber(6),
        'nombre' => $faker->word(),
        'presentacion' => $faker->sentence(2),
        'precio_por_mayor' => $faker->randomFloat(2, 0, 10000),
        'consumidor_final' => $faker->randomFloat(2, 0, 10000)
    ];
});
