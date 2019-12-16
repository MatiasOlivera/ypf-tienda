<?php

use App\Recurso;
use App\Empleado;
use App\EmpleadoPermiso;
use Faker\Generator as Faker;

$factory->define(Recurso::class, function (Faker $faker) {
    return [
        'nombre' => $faker->word()
    ];
});

$factory->afterCreatingState(Recurso::class, 'permisos', function ($recurso, $faker) {
    $empleado = factory(Empleado::class)->create();
    $permisos = factory(EmpleadoPermiso::class)->make()->toArray();
    $recurso->permisos()->attach($empleado, $permisos);
    $recurso->save();
});
