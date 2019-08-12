<?php

use App\Cliente;
use App\ClienteTelefono;
use Faker\Generator as Faker;

$factory->define(ClienteTelefono::class, function (Faker $faker) {
    return [
       'area' => $faker->randomNumber(5),
       'telefono' => $faker->numberBetween(100000, 999999),
       'nombreContacto' => $faker->optional()->firstName(),

       // Cliente
       'id_cliente' => function () {
           return factory(Cliente::class)->create()->id;
        }
    ];
});
