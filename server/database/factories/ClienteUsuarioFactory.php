<?php

use App\Cliente;
use App\ClienteUsuario;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(ClienteUsuario::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => Hash::make('12345678'),
        'remember_token' => str_random(10),

        'id_cliente' => function () {
            $cliente = factory(Cliente::class)->create();
            return $cliente->id;
        }
    ];
});
