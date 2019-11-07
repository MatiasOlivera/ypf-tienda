<?php

namespace Tests\Feature\app;

use App\Pedido;
use App\Cliente;
use App\Empleado;
use App\Cotizacion;
use Tests\TestCase;
use App\Observacion;
use PedidoEstadoSeeder;
use App\ClienteTelefono;
use App\ClienteDomicilio;
use App\CotizacionEstado;
use App\ClienteRazonSocial;
use App\CotizacionProducto;
use CategoriaProductoSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraCotizacion;

class CotizacionTest extends TestCase
{
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraCotizacion;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategoriaProductoSeeder::class);
    }

    public function test_deberia_crear_una_cotizacion()
    {
        $cotizacion = factory(Cotizacion::class)->create();
        $cotizacionDB = Cotizacion::findOrFail($cotizacion->id)->toArray();

        foreach ($this->atributosCotizacion as $atributo) {
            $this->assertArrayHasKey($atributo, $cotizacionDB);
        }
    }

    public function test_deberia_llenar_los_atributos_fillable_de_cotizacion()
    {
        $entrada = factory(Cotizacion::class)->make()->toArray();

        $cotizacion = new Cotizacion();
        $cotizacion->fill($entrada);
        $guardado = $cotizacion->save();

        $this->assertTrue($guardado);

        unset($entrada['id']);
        $this->assertArraySubset($entrada, $cotizacion->toArray());
    }

    public function test_deberia_acceder_a_la_relacion_empleado()
    {
        $cotizacion = factory(Cotizacion::class)->create();
        $empleado = $cotizacion->empleado;

        $this->assertInstanceOf(Empleado::class, $empleado);
        $this->assertEquals($cotizacion->empleado_id, $empleado->id);
    }

    public function test_deberia_acceder_a_la_relacion_cliente()
    {
        $cotizacion = factory(Cotizacion::class)->create();
        $cliente = $cotizacion->cliente;

        $this->assertInstanceOf(Cliente::class, $cliente);
        $this->assertEquals($cotizacion->cliente_id, $cliente->id);
    }

    public function test_deberia_acceder_a_la_relacion_razon_social()
    {
        $cotizacion = factory(Cotizacion::class)->create();
        $razonSocial = $cotizacion->razonSocial;

        $this->assertInstanceOf(ClienteRazonSocial::class, $razonSocial);
        $this->assertEquals($cotizacion->razon_id, $razonSocial->id);
    }

    public function test_deberia_acceder_a_la_relacion_estado()
    {
        $cotizacion = factory(Cotizacion::class)->create();
        $estado = $cotizacion->cotizacionEstado;

        $this->assertInstanceOf(CotizacionEstado::class, $estado);
        $this->assertEquals($cotizacion->estado_id, $estado->id);
    }

    public function test_deberia_acceder_a_la_relacion_telefono()
    {
        $cotizacion = factory(Cotizacion::class)->create();
        $telefono = $cotizacion->telefono;

        $this->assertInstanceOf(ClienteTelefono::class, $telefono);
        $this->assertEquals($cotizacion->telefono_id, $telefono->id);
    }

    public function test_deberia_acceder_a_la_relacion_domicilio()
    {
        $cotizacion = factory(Cotizacion::class)->create();
        $domicilio = $cotizacion->domicilio;

        $this->assertInstanceOf(ClienteDomicilio::class, $domicilio);
        $this->assertEquals($cotizacion->domicilio_id, $domicilio->id);
    }

    public function test_deberia_acceder_a_la_relacion_observacion()
    {
        $cotizacion = factory(Cotizacion::class)->states('observacion')->create();
        $observacion = $cotizacion->observacion;

        $this->assertInstanceOf(Observacion::class, $observacion);
        $this->assertEquals($cotizacion->observacion_id, $observacion->id);
    }

    public function test_deberia_acceder_a_la_relacion_pedido()
    {
        $this->seed(PedidoEstadoSeeder::class);

        $cotizacion = factory(Cotizacion::class)->states('pedido')->create();
        $pedido = $cotizacion->pedido;

        $this->assertInstanceOf(Pedido::class, $pedido);
        $this->assertEquals($cotizacion->pedido_id, $pedido->id);
    }

    public function test_deberia_acceder_a_la_relacion_productos()
    {
        $cotizacion = factory(Cotizacion::class)->states('productos')->create();
        $productos = $cotizacion->productos;

        foreach ($productos as $producto) {
            $this->assertInstanceOf(CotizacionProducto::class, $producto);
            $this->assertEquals($cotizacion->id, $producto->cotizacion_id);
        }
    }
}
