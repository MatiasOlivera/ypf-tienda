<?php

namespace Tests\Feature\app;

use App\Cotizacion;
use Tests\TestCase;
use App\ClienteRazonSocial;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteRazonSocialTest extends TestCase
{
    use RefreshDatabase;

    public function test_deberia_acceder_a_la_relacion_cotizaciones()
    {
        $razon = factory(ClienteRazonSocial::class)->create();

        $nuevaCotizacion = factory(Cotizacion::class)->make([
            'razon_id' => $razon->id
        ]);

        $razon->cotizaciones()->save($nuevaCotizacion);

        $cotizacion = $razon->cotizaciones()->first();

        $this->assertInstanceOf(Cotizacion::class, $cotizacion);
        $this->assertEquals($cotizacion->razon_id, $razon->id);
    }
}
