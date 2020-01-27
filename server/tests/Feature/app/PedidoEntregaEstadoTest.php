<?php

namespace Tests\Feature\app;

use App\Pedido;
use Tests\TestCase;
use PedidoEstadoSeeder;
use App\PedidoEntregaEstado;
use PedidoEntregaEstadoSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraPedidoEntregaEstado;

class PedidoEntregaEstadoTest extends TestCase
{
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraPedidoEntregaEstado;

    public function test_deberia_crear_un_estado()
    {
        $estado = factory(PedidoEntregaEstado::class)->create();
        $estadoDB = PedidoEntregaEstado::findOrFail($estado->id)->toArray();

        foreach ($this->atributosPedidoEntregaEstado as $atributo) {
            $this->assertArrayHasKey($atributo, $estadoDB);
        }
    }

    public function test_deberia_llenar_los_atributos_fillable_de_estado()
    {
        $entrada = factory(PedidoEntregaEstado::class)->make()->toArray();

        $estado = new PedidoEntregaEstado();
        $estado->fill($entrada);
        $guardado = $estado->save();

        $this->assertTrue($guardado);

        unset($entrada['id']);
        $this->assertArraySubset($entrada, $estado->toArray());
    }

    public function test_deberia_acceder_a_la_relacion_pedidos()
    {
        $this->seed(PedidoEstadoSeeder::class);
        $this->seed(PedidoEntregaEstadoSeeder::class);

        $nuevoPedido = factory(Pedido::class)->create();
        $estado = $nuevoPedido->entregaEstado;
        $pedido = $estado->pedidos()->get()->first();

        $this->assertInstanceOf(Pedido::class, $pedido);
        $this->assertEquals($estado->id, $pedido->entrega_estado_id);
    }
}
