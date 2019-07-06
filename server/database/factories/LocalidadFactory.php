<?php

use App\Localidad;
use App\Provincia;
use Faker\Generator as Faker;

$factory->define(Localidad::class, function (Faker $faker) {
    return [
        'nombre' => $faker->unique()->city(),
        'id_provincia' => function () {
            return factory(Provincia::class)->create()->id;
        }
    ];
});
