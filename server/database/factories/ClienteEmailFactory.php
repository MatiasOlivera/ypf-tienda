<?php

use App\Cliente;
use App\ClienteMail;
use Faker\Generator as Faker;

$factory->define(ClienteMail::class, function (Faker $faker) {
    return [
        'mail' => $faker->email(),
        'cliente_id' => function () {
            return factory(Cliente::class)->create()->id;
        }
    ];
});
