<?php

use App\CotizacionEstado;
use Faker\Generator as Faker;

$factory->define(CotizacionEstado::class, function (Faker $faker) {
    return [
        'descripcion' => $faker->unique()->text(11)
    ];
});
