<?php

namespace Tests\Feature\app;

use App\Cotizacion;
use Tests\TestCase;
use App\Observacion;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraObservacion;

class ObservacionTest extends TestCase
{
    use RefreshDatabase;
    use EstructuraObservacion;

    public function test_deberia_crear_una_observacion()
    {
        $observacion = factory(Observacion::class)->create()->toArray();

        foreach ($this->atributosObservacion as $atributo) {
            $this->assertArrayHasKey($atributo, $observacion);
        }

        $this->assertDatabaseHas('observacion', $observacion);
    }

    public function test_deberia_llenar_los_atributos_fillable_de_observacion()
    {
        $entrada = factory(Observacion::class)->make()->toArray();

        $observacion = new Observacion();
        $observacion->fill($entrada);
        $guardado = $observacion->save();

        $this->assertTrue($guardado);
        $this->assertDatabaseHas('observacion', $entrada);
    }

    public function test_deberia_acceder_a_la_relacion_cotizacion()
    {
        $cotizacion = factory(Cotizacion::class)->create();
        $observacion = $cotizacion->observacion;

        $this->assertInstanceOf(Cotizacion::class, $observacion->cotizacion);
        $this->assertEquals($cotizacion->observacion_id, $observacion->id);
    }
}
