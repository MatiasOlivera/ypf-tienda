<?php

namespace Tests\Feature\app;

use App\Cliente;
use App\Cotizacion;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    public function test_deberia_acceder_a_la_relacion_cotizaciones()
    {
        $cliente = factory(Cliente::class)->create();

        $nuevaCotizacion = factory(Cotizacion::class)->make([
            'cliente_id' => $cliente->id
        ]);

        $cliente->cotizaciones()->save($nuevaCotizacion);

        $cotizacion = $cliente->cotizaciones()->first();

        $this->assertInstanceOf(Cotizacion::class, $cotizacion);
        $this->assertEquals($cotizacion->cliente_id, $cliente->id);
    }
}
