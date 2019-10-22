<?php

use App\Cliente;
use App\ClienteRazonSocial;
use Faker\Generator as Faker;

$factory->define(Cliente::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name(),
        'documento' => $faker->randomNumber(8, true),
        'observacion' => $faker->sentence()
    ];
});

$factory->afterCreatingState(Cliente::class, 'razonesSociales', function ($cliente, $faker) {
    $razones = factory(ClienteRazonSocial::class, 2)->create();
    $cliente->razonesSociales()->attach($razones);
    $cliente->save();
});
