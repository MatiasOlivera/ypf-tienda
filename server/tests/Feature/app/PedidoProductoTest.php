<?php

namespace Tests\Feature\app;

use App\Pedido;
use App\Producto;
use Tests\TestCase;
use App\PedidoProducto;
use PedidoEstadoSeeder;
use CategoriaProductoSeeder;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraPedidoProducto;

class PedidoProductoTest extends TestCase
{
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraPedidoProducto;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CategoriaProductoSeeder::class);
        $this->seed(PedidoEstadoSeeder::class);

        $this->pedido = factory(Pedido::class)->create();
    }

    public function test_deberia_crear_un_producto()
    {
        $pedido = factory(Pedido::class)->states('productos')->create();
        $producto = $pedido->productos()->first()->toArray();

        foreach ($this->atributosPedidoProducto as $atributo) {
            $this->assertArrayHasKey($atributo, $producto);
        }
    }

    public function test_deberia_llenar_los_atributos_fillable_de_producto()
    {
        $entrada = factory(PedidoProducto::class)
            ->make(['pedido_id' => $this->pedido->id])
            ->toArray();

        $producto = new PedidoProducto();
        $producto->fill($entrada);
        $guardado = $producto->save();

        $this->assertTrue($guardado);

        unset($entrada['id']);
        $this->assertArraySubset($entrada, $producto->toArray());
    }

    public function test_deberia_acceder_a_la_relacion_pedido()
    {
        $producto = factory(PedidoProducto::class)
            ->make(['pedido_id' => $this->pedido->id]);

        $pedido = $producto->pedido;

        $this->assertInstanceOf(Pedido::class, $pedido);
        $this->assertEquals($pedido->id, $producto->pedido_id);
    }

    public function test_deberia_acceder_a_la_relacion_producto()
    {
        $pedidoProducto = factory(PedidoProducto::class)
            ->make(['pedido_id' => $this->pedido->id]);

        $producto = $pedidoProducto->producto;

        $this->assertInstanceOf(Producto::class, $producto);
        $this->assertEquals($producto->codigo, $pedidoProducto->codigo);
    }
}
