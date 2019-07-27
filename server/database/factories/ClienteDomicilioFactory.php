<?php

use App\Cliente;
use App\Localidad;
use Faker\Generator as Faker;

$factory->define(App\ClienteDomicilio::class, function (Faker $faker) {
    return [
        'calle' => $faker->streetName(),
        'numero' => $faker->randomNumber(4),
        'aclaracion' => $faker->sentence(),
        'localidad_id' => function () {
            return factory(Localidad::class)->create()->id;
        },
        'cliente_id' => function () {
            return factory(Cliente::class)->create()->id;
        },
    ];
});
