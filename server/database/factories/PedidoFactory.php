<?php

use App\Pedido;
use App\Cliente;
use App\Empleado;
use App\Observacion;
use App\PedidoEntregaEstado;
use App\PedidoProducto;
use App\ClienteTelefono;
use App\ClienteDomicilio;
use App\CotizacionEstado;
use App\ClienteRazonSocial;
use Faker\Generator as Faker;

$factory->define(Pedido::class, function (Faker $faker) {
    return [
        'fecha_pedido' => $faker->date(),
        'consumidor_final' => $faker->randomElement(['0', '1']),
        'plazo' => $faker->word(),
        'cotizacion_estado_id' => function () {
            return factory(CotizacionEstado::class)->create()->id;
        },
        'entrega_estado_id' => function () {
            $estado = PedidoEntregaEstado::inRandomOrder()->first();

            if ($estado === null) {
                throw new Exception("Debes usar el seeder PedidoEntregaEstadoSeeder");
            }

            return $estado->id;
        },
        'observacion_id' => function () {
            return factory(Observacion::class)->create()->id;
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

$factory->afterCreatingState(Pedido::class, 'productos', function ($pedido, $faker) {
    $productos = factory(PedidoProducto::class, 5)->make();
    $pedido->productos()->saveMany($productos);
    $pedido->save();
});
