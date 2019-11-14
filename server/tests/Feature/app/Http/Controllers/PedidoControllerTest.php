<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Pedido;
use App\Cotizacion;
use Tests\TestCase;
use App\PedidoEstado;
use PedidoEstadoSeeder;
use App\PedidoEntregaEstado;
use CategoriaProductoSeeder;
use PedidoEntregaEstadoSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use App\Http\Resources\Pedido\PedidoResource;
use Tests\Feature\Utilidades\EstructuraPedido;
use App\Http\Resources\Pedido\PedidoCollection;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;
use App\Http\Resources\Cotizacion\CotizacionResource;

class PedidoControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraJsonHelper;
    use EstructuraPedido;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PedidoEstadoSeeder::class);
        $this->seed(PedidoEntregaEstadoSeeder::class);
        $this->seed(CategoriaProductoSeeder::class);
    }

    private function crearCotizacion()
    {
        $nuevaCotizacion = factory(Cotizacion::class)
            ->states('observacion', 'productos')
            ->create()
            ->toArray();

        return $this->getCotizacionConProductos($nuevaCotizacion);
    }

    private function getCotizacionConProductos($cotizacion)
    {
        $productos = $this->getCotizacion($cotizacion['id'])['productos'];

        foreach ($productos as $producto) {
            $cotizacion['productos'][] = [
                'id' => $producto['id'],
                'producto_id' => $producto['producto']['id'],
                'cantidad' => $producto['cantidad'],
                'precio' => $producto['precio']
            ];
        }

        return $cotizacion;
    }

    private function getCotizacion($id): array
    {
        $cotizacion = Cotizacion::withTrashed()
            ->with([
                'empleado',
                'cliente',
                'razonSocial',
                'cotizacionEstado',
                'telefono',
                'domicilio',
                'observacion',
                'pedido',
                'productos.producto'
            ])
            ->findOrFail($id);
        $recurso = new CotizacionResource($cotizacion);
        $respuesta = $recurso->response()->getData(true);
        return $respuesta['cotizacion'];
    }

    private function getPedido($id): array
    {
        $pedido = Pedido::with([
            'empleado',
            'cliente',
            'razonSocial',
            'pedidoEstado',
            'telefono',
            'domicilio',
            'observacion',
            'productos.producto'
        ])
        ->findOrFail($id);
        $recurso = new PedidoResource($pedido);
        $respuesta = $recurso->response()->getData(true);
        return $respuesta['pedido'];
    }

    private function getEstructuraPedidos(): array
    {
        $paginacion = $this->estructuraPaginacion;
        unset($paginacion['paginacion']['rutas']);
        return array_merge(['pedidos'], $paginacion);
    }

    public function test_no_deberia_obtener_ningun_pedido()
    {
        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/pedidos');

        $estructura = $this->getEstructuraPedidos();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['pedidos' => []]);
    }

    public function test_deberia_obtener_pedidos()
    {
        factory(Pedido::class, 10)->create();

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/pedidos');

        $estructura = $this->getEstructuraPedidos();

        $pedidosTabla = Pedido::orderBy('id', 'DESC')->paginate(10);
        $pedidosColeccion = new PedidoCollection($pedidosTabla);
        $pedidosRespuesta = $pedidosColeccion->response()->getData(true);

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson($pedidosRespuesta);
    }

    public function test_deberia_obtener_pedidos_pendientes()
    {
        factory(Pedido::class, 10)->create();

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/pedidos?entrega_estado=pendiente");

        $estructura = $this->getEstructuraPedidos();

        $estadoPendiente = PedidoEntregaEstado::where('descripcion', 'Pendiente')->first();
        $estadoEntregaParcial = PedidoEntregaEstado::where('descripcion', 'Entrega Parcial')->first();

        $pedidosTabla = Pedido::where('entrega_estado_id', $estadoPendiente->id)
            ->orWhere('entrega_estado_id', $estadoEntregaParcial->id)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $pedidosColeccion = new PedidoCollection($pedidosTabla);
        $pedidosRespuesta = $pedidosColeccion->response()->getData(true);

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson($pedidosRespuesta);
    }

    public function test_deberia_crear_un_pedido()
    {
        $cotizacion = $this->crearCotizacion();
        $cotizacionId = $cotizacion['id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/pedidos', ['cotizacion_id' => $cotizacionId]);

        $id = $respuesta->getData(true)['pedido']['id'];
        $pedidoRespuesta = $this->getPedido($id);

        $respuesta
            ->assertStatus(201)
            ->assertExactJson([
                'pedido' => $pedidoRespuesta,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => 'El pedido ha sido creado'
                ]
            ]);

        $pedidoEsperado = $this->getCotizacion($cotizacionId);
        $pedidoEsperado['id'] = $id;

        // Quitar atributos que se encuentran en la cotización pero no en el pedido
        unset($pedidoEsperado['pedido_id']);
        unset($pedidoEsperado['created_at']);
        unset($pedidoEsperado['updated_at']);
        unset($pedidoEsperado['deleted_at']);

        // Establecer el estado por defecto del pedido
        unset($pedidoEsperado['estado']);

        $estadoPendiente = PedidoEstado::where('descripcion', 'Pendiente')->first();
        $pedidoEsperado['estado'] = $estadoPendiente->toArray();

        $pedidoEsperadoProductos = $pedidoEsperado['productos'];
        $productosRespuesta = $respuesta->getData(true)['pedido']['productos'];
        unset($pedidoEsperado['productos']);
        unset($pedidoRespuesta['productos']);

        $this->assertEquals($pedidoEsperado, $pedidoRespuesta);

        $productosRespuestaSinId = [];
        foreach ($productosRespuesta as $producto) {
            unset($producto['id']);
            $productosRespuestaSinId[] = $producto;
        }

        // Quitar o agregar atributos en productos
        $productosEsperados = [];

        foreach ($pedidoEsperadoProductos as $producto) {
            unset($producto['id']);
            unset($producto['cotizacion_id']);
            unset($producto['deleted_at']);

            $producto['pedido_id'] = $id;

            $productosEsperados[] = $producto;
        }

        $this->assertEqualsCanonicalizing($productosEsperados, $productosRespuestaSinId);
    }

    public function test_no_deberia_crear_un_pedido_cuando_hay_un_pedido_existente()
    {
        $cotizacion = $this->crearCotizacion();
        $cotizacionId = $cotizacion['id'];

        $cabeceras = $this->loguearseComo('defecto');
        $primeraRespuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/pedidos', ['cotizacion_id' => $cotizacionId]);

        $primeraRespuesta->assertStatus(201);

        $segundaRespuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/pedidos', ['cotizacion_id' => $cotizacionId]);

        $segundaRespuesta
            ->assertStatus(500)
            ->assertExactJson([
                'mensaje' => [
                    'tipo' => 'error',
                    'codigo' => 'EXISTENTE',
                    'descripcion' => 'El pedido no ha sido creado debido a que ya existe un pedido relacionado con esta cotización'
                ]
            ]);
    }
}
