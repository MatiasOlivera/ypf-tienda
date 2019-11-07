<?php

namespace Tests\Feature\app;

use App\Pedido;
use App\Cliente;
use App\Empleado;
use App\Cotizacion;
use Tests\TestCase;
use App\Observacion;
use App\PedidoEstado;
use App\PedidoProducto;
use PedidoEstadoSeeder;
use App\ClienteTelefono;
use App\ClienteDomicilio;
use App\CotizacionEstado;
use App\ClienteRazonSocial;
use CategoriaProductoSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EstructuraPedido;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PedidoTest extends TestCase
{
    use RefreshDatabase;
    use EstructuraPedido;
    use EloquenceSolucion;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CategoriaProductoSeeder::class);
        $this->seed(PedidoEstadoSeeder::class);
    }

    public function test_deberia_crear_un_pedido()
    {
        $pedido = factory(Pedido::class)->create();
        $pedidoDB = Pedido::findOrFail($pedido->id)->toArray();

        foreach ($this->atributosPedido as $atributo) {
            $this->assertArrayHasKey($atributo, $pedidoDB);
        }
    }

    public function test_deberia_llenar_los_atributos_fillable_de_pedido()
    {
        $entrada = factory(Pedido::class)->make()->toArray();

        $pedido = new Pedido();
        $pedido->fill($entrada);
        $guardado = $pedido->save();

        $this->assertTrue($guardado);

        unset($entrada['id']);
        $this->assertArraySubset($entrada, $pedido->toArray());
    }

    public function test_deberia_acceder_a_la_relacion_productos()
    {
        $pedido = factory(Pedido::class)->states('productos')->create();
        $productos = $pedido->productos;

        foreach ($productos as $producto) {
            $this->assertInstanceOf(PedidoProducto::class, $producto);
            $this->assertEquals($pedido->id, $producto->pedido_id);
        }
    }

    public function test_deberia_acceder_a_la_relacion_empleado()
    {
        $pedido = factory(Pedido::class)->create();
        $empleado = $pedido->empleado;

        $this->assertInstanceOf(Empleado::class, $empleado);
        $this->assertEquals($pedido->empleado_id, $empleado->id);
    }

    public function test_deberia_acceder_a_la_relacion_cliente()
    {
        $pedido = factory(Pedido::class)->create();
        $cliente = $pedido->cliente;

        $this->assertInstanceOf(Cliente::class, $cliente);
        $this->assertEquals($pedido->cliente_id, $cliente->id);
    }

    public function test_deberia_acceder_a_la_relacion_razon_social()
    {
        $pedido = factory(Pedido::class)->create();
        $razonSocial = $pedido->razonSocial;

        $this->assertInstanceOf(ClienteRazonSocial::class, $razonSocial);
        $this->assertEquals($pedido->razon_id, $razonSocial->id);
    }

    public function test_deberia_acceder_a_la_relacion_pedido_estado()
    {
        $pedido = factory(Pedido::class)->create();
        $estado = $pedido->pedidoEstado;

        $this->assertInstanceOf(PedidoEstado::class, $estado);
        $this->assertEquals($pedido->pedido_estado_id, $estado->id);
    }

    public function test_deberia_acceder_a_la_relacion_cotizacion_estado()
    {
        $pedido = factory(Pedido::class)->create();
        $estado = $pedido->cotizacionEstado;

        $this->assertInstanceOf(CotizacionEstado::class, $estado);
        $this->assertEquals($pedido->cotizacion_estado_id, $estado->id);
    }

    public function test_deberia_acceder_a_la_relacion_telefono()
    {
        $pedido = factory(Pedido::class)->create();
        $telefono = $pedido->telefono;

        $this->assertInstanceOf(ClienteTelefono::class, $telefono);
        $this->assertEquals($pedido->telefono_id, $telefono->id);
    }

    public function test_deberia_acceder_a_la_relacion_domicilio()
    {
        $pedido = factory(Pedido::class)->create();
        $domicilio = $pedido->domicilio;

        $this->assertInstanceOf(ClienteDomicilio::class, $domicilio);
        $this->assertEquals($pedido->domicilio_id, $domicilio->id);
    }

    public function test_deberia_acceder_a_la_relacion_observacion()
    {
        $pedido = factory(Pedido::class)->create();
        $observacion = $pedido->observacion;

        $this->assertInstanceOf(Observacion::class, $observacion);
        $this->assertEquals($pedido->observacion_id, $observacion->id);
    }

    public function test_deberia_acceder_a_la_relacion_cotizacion()
    {
        $cotizacion = factory(Cotizacion::class)->create();
        $pedido = $cotizacion->pedido;

        $this->assertInstanceOf(Cotizacion::class, $pedido->cotizacion);
        $this->assertEquals($pedido->id, $cotizacion->pedido_id);
    }
}
