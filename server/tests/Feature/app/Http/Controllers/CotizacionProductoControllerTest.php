<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Producto;
use App\Cotizacion;
use Tests\ApiTestCase;
use App\CotizacionProducto;
use CotizacionEstadoSeeder;
use CategoriaProductoSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraCotizacion;
use Tests\Feature\Utilidades\EstructuraJsonHelper;
use App\Http\Resources\Cotizacion\CotizacionResource;
use Tests\Feature\Utilidades\Api\CotizacionProductoApi;

class CotizacionProductoControllerTest extends ApiTestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraJsonHelper;
    use CotizacionProductoApi;
    use EstructuraCotizacion;

    protected $usuario;
    protected $cabeceras;
    protected $clienteID;
    protected $cotizacion;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CotizacionEstadoSeeder::class);
        $this->seed(CategoriaProductoSeeder::class);

        $login = $this->loguearseComoCliente();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];
        $this->clienteID = $this->usuario->id_cliente;

        $this->cotizacion = $this->crearCotizacion();
    }

    private function crearCotizacion()
    {
        $cotizacion = factory(Cotizacion::class)
            ->states('observacion', 'productos')
            ->create(['cliente_id' => $this->clienteID])
            ->toArray();

        $productos = $this->getCotizacionProductos($cotizacion['id']);

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

    private function getCotizacionProductos($id): array
    {
        $cotizacion = Cotizacion::with('productos.producto')->findOrFail($id);
        return $cotizacion->productos->toArray();
    }

    public function test_el_cliente_usuario_deberia_actualizar_un_producto_en_una_cotizacion()
    {
        $id = $this->cotizacion['id'];
        $productos = $this->cotizacion['productos'];

        $productoActualizado = $productos[0];
        $productoActualizado['cantidad'] = 10;
        $productoActualizado['precio'] = 5000;
        $productos[0] = $productoActualizado;

        $respuesta = $this->actualizarCotizacionProductos($id, $productos);

        $productosRespuesta = $this->getCotizacionProductos($id);

        $respuesta
            ->assertOk()
            ->assertExactJson([
                'productos' => $productosRespuesta,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'Los productos han sido modificados'
                ]
            ]);

        foreach ($productosRespuesta as $producto) {
            if ($productoActualizado['id'] === $producto['id']) {
                $this->assertEquals($productoActualizado['cantidad'], $producto['cantidad']);
                $this->assertEquals($productoActualizado['precio'], $producto['precio']);
            }
        }
    }

    public function test_el_cliente_usuario_deberia_agregar_un_producto_a_una_cotizacion()
    {
        $id = $this->cotizacion['id'];
        $productos = $this->cotizacion['productos'];

        $nuevoProducto = factory(CotizacionProducto::class)
            ->make(['cotizacion_id' => $id])
            ->toArray();
        $producto = Producto::where('codigo', $nuevoProducto['codigo'])->first();
        $nuevoProducto['producto_id'] = $producto->id;
        $productos[] = $nuevoProducto;

        $respuesta = $this->actualizarCotizacionProductos($id, $productos);

        $productosRespuesta = $this->getCotizacionProductos($id);

        $respuesta
            ->assertOk()
            ->assertExactJson([
                'productos' => $productosRespuesta,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'Los productos han sido modificados'
                ]
            ]);

        $this->assertCount(6, $respuesta->getData(true)['productos']);
    }

    public function test_el_cliente_usuario_deberia_agregar_un_producto_duplicado_en_una_cotizacion()
    {
        $id = $this->cotizacion['id'];
        $productos = $this->cotizacion['productos'];

        $primerProducto = $productos[0];
        $productoDuplicado = $primerProducto;
        $productoDuplicado['id'] = null;
        $productos[] = $productoDuplicado;

        $respuesta = $this->actualizarCotizacionProductos($id, $productos);

        $productosEsperados = $this->getCotizacionProductos($id);

        $respuesta
            ->assertOk()
            ->assertExactJson([
                'productos' => $productosEsperados,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'Los productos han sido modificados'
                ]
            ]);

        $productosRespuesta = $respuesta->getData(true)['productos'];

        $this->assertCount(5, $productosRespuesta);

        foreach ($productosRespuesta as $producto) {
            if ($primerProducto['id'] === $producto['id']) {
                $cantidad = $primerProducto['cantidad'] + $productoDuplicado['cantidad'];

                $this->assertEquals($cantidad, $producto['cantidad']);
                $this->assertEquals($productoDuplicado['precio'], $producto['precio']);
            }
        }
    }

    public function test_el_cliente_usuario_deberia_agregar_un_producto_eliminado_en_una_cotizacion()
    {
        $id = $this->cotizacion['id'];
        $productos = $this->cotizacion['productos'];

        $producto = $productos[0];

        $respuesta = $this->eliminarCotizacionProducto($producto['id']);

        $producto['id'] = null;
        $producto['cantidad'] = 10;
        $producto['precio'] = 100;
        $productos[0] = $producto;

        $respuesta = $this->actualizarCotizacionProductos($id, $productos);

        $productosEsperados = $this->getCotizacionProductos($id);

        $respuesta
            ->assertOk()
            ->assertExactJson([
                'productos' => $productosEsperados,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'Los productos han sido modificados'
                ]
            ]);

        $productosRespuesta = $respuesta->getData(true)['productos'];

        $this->assertCount(5, $productosRespuesta);

        $productoId = $producto['producto_id'];
        $productoEsperado = Producto::find($productoId);
        $codigo = $productoEsperado->codigo;

        foreach ($productosRespuesta as $producto) {
            if ($codigo === $producto['codigo']) {
                $this->assertEquals("10.00", $producto['cantidad']);
                $this->assertEquals("100.00", $producto['precio']);
            }
        }
    }

    public function test_el_cliente_usuario_deberia_eliminar_un_producto_de_una_cotizacion()
    {
        $id = $this->cotizacion['productos'][0]['id'];

        $cotizacionProductoEsperado = CotizacionProducto::with('producto')->findOrFail($id);
        $nombre = $cotizacionProductoEsperado->producto->nombre;

        $respuesta = $this->eliminarCotizacionProducto($id);

        $cotizacionProductoEsperadoArray = $cotizacionProductoEsperado->toArray();
        unset($cotizacionProductoEsperadoArray['deleted_at']);

        $respuesta
            ->assertOk()
            ->assertJson([
                'producto' => $cotizacionProductoEsperadoArray,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ELIMINADO',
                    'descripcion' => "El producto $nombre ha sido eliminado"
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['producto']['deleted_at'];
        $this->assertNotNull($deletedAt);
    }
}
