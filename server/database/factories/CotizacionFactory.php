<?php

use App\Pedido;
use App\Cliente;
use App\Empleado;
use App\Cotizacion;
use App\Observacion;
use App\ClienteTelefono;
use App\ClienteDomicilio;
use App\CotizacionEstado;
use App\ClienteRazonSocial;
use App\CotizacionProducto;
use Faker\Generator as Faker;

$factory->define(Cotizacion::class, function (Faker $faker) {
    return [
        'fecha_pedido' => $faker->date(),
        'consumidor_final' => $faker->randomElement(['0', '1']),
        'plazo' => $faker->word(),
        'estado_id' => function () {
            return factory(CotizacionEstado::class)->create()->id;
        },

        /**
         * Empleado
         */
        'empleado_id' => function () {
            return factory(Empleado::class)->create()->id;
        },

        /**
         * Cliente
         */
        'cliente_id' => function () {
            return factory(Cliente::class)->create()->id;
        },
        'razon_id' => function () {
            return factory(ClienteRazonSocial::class)->create()->id;
        },
        'telefono_id' => function () {
            return factory(ClienteTelefono::class)->create()->id;
        },
        'domicilio_id' => function () {
            return factory(ClienteDomicilio::class)->create()->id;
        }
    ];
});

$factory->afterCreatingState(Cotizacion::class, 'productos', function ($cotizacion, $faker) {
    $productos = factory(CotizacionProducto::class, 5)->make();
    $cotizacion->productos()->saveMany($productos);
    $cotizacion->save();
});

$factory->afterCreatingState(Cotizacion::class, 'observacion', function ($cotizacion, $faker) {
    $observacion = factory(Observacion::class)->create();
    $cotizacion->observacion_id = $observacion->id;
    $cotizacion->save();
});

$factory->afterCreatingState(Cotizacion::class, 'pedido', function ($cotizacion, $faker) {
    $pedido = factory(Pedido::class)->create();
    $cotizacion->pedido_id = $pedido->id;
    $cotizacion->save();
});
