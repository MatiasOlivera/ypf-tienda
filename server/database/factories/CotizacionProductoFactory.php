<?php

use App\Producto;
use App\CotizacionProducto;
use Faker\Generator as Faker;

$factory->define(CotizacionProducto::class, function (Faker $faker) {
    return [
        'codigo' => function () {
            return factory(Producto::class)->create()->codigo;
        },
        'cantidad' => $faker->randomFloat(2, 1, 100),
        'precio' => $faker->randomFloat(2, 1, 10000)
    ];
});
