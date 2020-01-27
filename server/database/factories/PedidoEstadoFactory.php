<?php

use App\PedidoEstado;
use Faker\Generator as Faker;

$factory->define(PedidoEstado::class, function (Faker $faker) {
    return [
        'descripcion' => $faker->unique()->text(11)
    ];
});
