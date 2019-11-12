<?php

namespace Tests\Feature\app;

use App\Pedido;
use App\Cotizacion;
use Tests\TestCase;
use App\Observacion;
use PedidoEstadoSeeder;
use PedidoEntregaEstadoSeeder;
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
        $cotizacion = factory(Cotizacion::class)->states('observacion')->create();
        $observacion = $cotizacion->observacion;

        $this->assertInstanceOf(Cotizacion::class, $observacion->cotizacion);
        $this->assertEquals($cotizacion->observacion_id, $observacion->id);
    }

    public function test_deberia_acceder_a_la_relacion_pedido()
    {
        $this->seed(PedidoEstadoSeeder::class);
        $this->seed(PedidoEntregaEstadoSeeder::class);

        $pedido = factory(Pedido::class)->create();
        $observacion = $pedido->observacion;

        $this->assertInstanceOf(Pedido::class, $observacion->pedido);
        $this->assertEquals($pedido->observacion_id, $observacion->id);
    }
}
