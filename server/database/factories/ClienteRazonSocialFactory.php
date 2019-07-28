<?php

use App\Cliente;
use App\Localidad;
use App\ClienteRazonSocial;
use Faker\Generator as Faker;

$factory->define(ClienteRazonSocial::class, function (Faker $faker) {
    // CUIT
    $tipo = $faker->randomNumber(2);
    $numero = $faker->numberBetween(10000000, 99999999);
    $digitoVerificador = $faker->randomDigit();
    $cuit = "$tipo-$numero-$digitoVerificador";

    return [
        'denominacion' => $faker->company(),
        'cuit' => $cuit,
        'email' => $faker->email,

        // Teléfono
        'area' => $faker->randomNumber(5),
        'telefono' => $faker->numberBetween(100000, 999999),

        // Domicilio
        'calle' => $faker->streetName(),
        'altura' => $faker->randomNumber(4),
        'localidad_id'=> function () {
            return factory(Localidad::class)->create()->id;
        }
    ];
});
