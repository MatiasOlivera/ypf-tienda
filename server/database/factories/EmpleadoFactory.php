<?php

use App\Recurso;
use App\Empleado;
use App\EmpleadoCargo;
use App\EmpleadoPermiso;
use Faker\Generator as Faker;

$factory->define(Empleado::class, function (Faker $faker) {
    return [
        'documento' => $faker->randomNumber(8, true),
        'nombre' => $faker->firstname(),
        'apellido' => $faker->lastname(),
        'fecha_nacimiento' => $faker->optional()->date,
        'sexo' => $faker->optional()->randomElement(['F', 'M']),
        'password' => Hash::make('12345678'),

        // Cargo
        'cargo_id' => function () {
            return factory(EmpleadoCargo::class)->create()->id;
        }
    ];
});

$factory->afterCreatingState(Empleado::class, 'permisos', function ($empleado, $faker) {
    $recurso = factory(Recurso::class)->create();
    $permisos = factory(EmpleadoPermiso::class)->make()->toArray();
    $empleado->permisos()->attach($recurso, $permisos);
    $empleado->save();
});
