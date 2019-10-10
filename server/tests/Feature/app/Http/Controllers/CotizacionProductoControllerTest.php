<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Producto;
use App\Cotizacion;
use Tests\TestCase;
use App\CotizacionProducto;
use CotizacionEstadoSeeder;
use CategoriaProductoSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraCotizacion;
use Tests\Feature\Utilidades\EstructuraJsonHelper;
use App\Http\Resources\Cotizacion\CotizacionResource;

class CotizacionProductoControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraJsonHelper;
    use EstructuraCotizacion;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CotizacionEstadoSeeder::class);
        $this->seed(CategoriaProductoSeeder::class);
    }

    private function crearCotizacion()
    {
        $cotizacion = factory(Cotizacion::class)
            ->states('observacion', 'productos')
            ->create()
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

    public function test_deberia_actualizar_un_producto_en_una_cotizacion()
    {
        $cotizacion = $this->crearCotizacion();
        $id = $cotizacion['id'];
        $productos = $cotizacion['productos'];

        $productoActualizado = $productos[0];
        $productoActualizado['cantidad'] = 10;
        $productoActualizado['precio'] = 5000;
        $productos[0] = $productoActualizado;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('PUT', "api/cotizaciones/$id/productos", ["productos" => $productos]);

        $productosRespuesta = $this->getCotizacionProductos($id);

        $respuesta
            ->assertStatus(200)
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

    public function test_deberia_agregar_un_producto_a_una_cotizacion()
    {
        $cotizacion = $this->crearCotizacion();
        $id = $cotizacion['id'];
        $productos = $cotizacion['productos'];

        $nuevoProducto = factory(CotizacionProducto::class)
            ->make(['cotizacion_id' => $id])
            ->toArray();
        $producto = Producto::where('codigo', $nuevoProducto['codigo'])->first();
        $nuevoProducto['producto_id'] = $producto->id;
        $productos[] = $nuevoProducto;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('PUT', "api/cotizaciones/$id/productos", ["productos" => $productos]);

        $productosRespuesta = $this->getCotizacionProductos($id);

        $respuesta
            ->assertStatus(200)
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

    public function test_deberia_agregar_un_producto_duplicado_en_una_cotizacion()
    {
        $cotizacion = $this->crearCotizacion();
        $id = $cotizacion['id'];
        $productos = $cotizacion['productos'];

        $primerProducto = $productos[0];
        $productoDuplicado = $primerProducto;
        $productoDuplicado['id'] = null;
        $productos[] = $productoDuplicado;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('PUT', "api/cotizaciones/$id/productos", ["productos" => $productos]);

        $productosEsperados = $this->getCotizacionProductos($id);

        $respuesta
            ->assertStatus(200)
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

    public function test_deberia_agregar_un_producto_eliminado_en_una_cotizacion()
    {
        $cotizacion = $this->crearCotizacion();
        $cotizacionId = $cotizacion['id'];
        $productos = $cotizacion['productos'];

        $producto = $productos[0];

        $cabeceras = $this->loguearseComo('defecto');

        $cotizacionProductoId = $producto['id'];
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/cotizaciones/$cotizacionId/productos/$cotizacionProductoId");

        $producto['id'] = null;
        $producto['cantidad'] = 10;
        $producto['precio'] = 100;
        $productos[0] = $producto;
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('PUT', "api/cotizaciones/$cotizacionId/productos", ["productos" => $productos]);

        $productosEsperados = $this->getCotizacionProductos($cotizacionId);

        $respuesta
            ->assertStatus(200)
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

    public function test_deberia_eliminar_un_producto_de_una_cotizacion()
    {
        $cotizacion = $this->crearCotizacion();
        $cotizacionId = $cotizacion['id'];
        $cotizacionProducto = $cotizacion['productos'][0];
        $id = $cotizacionProducto['id'];

        $cotizacionProductoEsperado = CotizacionProducto::with('producto')->findOrFail($id);
        $nombre = $cotizacionProductoEsperado->producto->nombre;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/cotizaciones/$cotizacionId/productos/$id");

        $cotizacionProductoEsperadoArray = $cotizacionProductoEsperado->toArray();
        unset($cotizacionProductoEsperadoArray['deleted_at']);

        $respuesta
            ->assertStatus(200)
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
