<?php

namespace Tests\Feature\app;

use App\Cotizacion;
use Tests\TestCase;
use App\ClienteDomicilio;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteDomicilioTest extends TestCase
{
    use RefreshDatabase;
    use EloquenceSolucion;

    public function test_deberia_acceder_a_la_relacion_cotizaciones()
    {
        $domicilio = factory(ClienteDomicilio::class)->create();

        $nuevaCotizacion = factory(Cotizacion::class)->make([
            'domicilio_id' => $domicilio->id
        ]);

        $domicilio->cotizaciones()->save($nuevaCotizacion);

        $cotizacion = $domicilio->cotizaciones()->first();

        $this->assertInstanceOf(Cotizacion::class, $cotizacion);
        $this->assertEquals($cotizacion->domicilio_id, $domicilio->id);
    }
}
