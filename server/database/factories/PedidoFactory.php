<?php

use App\Pedido;
use App\Cliente;
use App\Empleado;
use App\Observacion;
use App\PedidoEstado;
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
        'pedido_estado_id' => function () {
            $estado = PedidoEstado::inRandomOrder()->first();

            if ($estado === null) {
                throw new Exception("Debes usar el seeder PedidoEstadoSeeder");
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
