<?php

use App\PedidoEntregaEstado;
use Faker\Generator as Faker;

$factory->define(PedidoEntregaEstado::class, function (Faker $faker) {
    return [
        'descripcion' => $faker->unique()->word()
    ];
});
