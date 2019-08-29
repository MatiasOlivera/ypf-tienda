<?php

namespace Tests\Feature\app;

use App\Cotizacion;
use Tests\TestCase;
use App\ClienteTelefono;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteTelefonoTest extends TestCase
{
    use RefreshDatabase;

    public function test_deberia_acceder_a_la_relacion_cotizaciones()
    {
        $telefono = factory(ClienteTelefono::class)->create();

        $nuevaCotizacion = factory(Cotizacion::class)->make([
            'telefono_id' => $telefono->id
        ]);

        $telefono->cotizaciones()->save($nuevaCotizacion);

        $cotizacion = $telefono->cotizaciones()->first();

        $this->assertInstanceOf(Cotizacion::class, $cotizacion);
        $this->assertEquals($cotizacion->telefono_id, $telefono->id);
    }
}
