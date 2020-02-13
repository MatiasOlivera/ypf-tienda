<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Cliente;
use App\Producto;
use App\Cotizacion;
use App\Observacion;
use Tests\ApiTestCase;
use App\CotizacionProducto;
use CotizacionEstadoSeeder;
use CategoriaProductoSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\Api\CotizacionApi;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraCotizacion;
use Tests\Feature\Utilidades\EstructuraJsonHelper;
use App\Http\Resources\Cotizacion\CotizacionResource;
use App\Http\Resources\Cotizacion\CotizacionCollection;

class CotizacionControllerComoClienteTest extends ApiTestCase
{
    use AuthHelper;
    use CotizacionApi;
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraJsonHelper;
    use EstructuraCotizacion;

    protected $usuario;
    protected $cabeceras;
    protected $clienteID;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CotizacionEstadoSeeder::class);
        $this->seed(CategoriaProductoSeeder::class);

        $login = $this->loguearseComoCliente();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];
        $this->clienteID = $this->usuario->id_cliente;
    }

    private function crearCotizacion()
    {
        $nuevaCotizacion = factory(Cotizacion::class)
            ->states('observacion', 'productos')
            ->create(['cliente_id' => $this->clienteID])
            ->toArray();

        return $this->getCotizacionConProductos($nuevaCotizacion);
    }

    private function crearCotizacionSinObservacion()
    {
        $nuevaCotizacion = factory(Cotizacion::class)
            ->states('productos')
            ->create(['cliente_id' => $this->clienteID])
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

    private function getEstructuraCotizaciones(): array
    {
        $paginacion = $this->estructuraPaginacion;
        unset($paginacion['paginacion']['rutas']);
        return array_merge(['cotizaciones'], $paginacion);
    }

    private function getCotizacion($id): array
    {
        $cotizacion = Cotizacion::withTrashed()->
            with([
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

    public function test_el_cliente_usuario_deberia_obtener_las_cotizaciones_propias()
    {
        factory(Cotizacion::class, 5)->create([
            'cliente_id' => $this->clienteID
        ]);

        $respuesta = $this->obtenerCotizacionesDelCliente($this->clienteID);

        $estructura = $this->getEstructuraCotizaciones();

        $cliente = Cliente::find($this->clienteID);
        $cotizacionesTabla = $cliente->cotizaciones()->orderBy('created_at', 'DESC')->paginate(10);
        $cotizacionesColeccion = new CotizacionCollection($cotizacionesTabla);
        $cotizacionesRespuesta = $cotizacionesColeccion->response()->getData(true);

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson($cotizacionesRespuesta);
    }

    public function test_el_cliente_usuario_deberia_crear_una_cotizacion()
    {
        $nuevaCotizacion = $this->crearCotizacion();

        $respuesta = $this->crearCotizacionDelCliente($this->clienteID, $nuevaCotizacion);

        $id = $respuesta->getData(true)['cotizacion']['id'];
        $cotizacionRespuesta = $this->getCotizacion($id);

        $respuesta
            ->assertStatus(201)
            ->assertExactJson([
                'cotizacion' => $cotizacionRespuesta,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => 'La cotización ha sido creada'
                ]
            ]);
    }

    public function test_el_cliente_usuario_deberia_obtener_una_cotizacion()
    {
        $cotizacionGuardada = $this->crearCotizacion();
        $id = $cotizacionGuardada['id'];

        $respuesta = $this->obtenerCotizacion($id);

        $cotizacionRespuesta = $this->getCotizacion($id);

        $respuesta
            ->assertOk()
            ->assertExactJson([
                'cotizacion' => $cotizacionRespuesta
            ]);
    }

    public function test_el_cliente_usuario_deberia_actualizar_una_cotizacion()
    {
        $cotizacion = $this->crearCotizacion();
        $id = $cotizacion['id'];

        $cotizacionActualizada = factory(Cotizacion::class)->make()->toArray();
        $cotizacionActualizada['productos'] = $cotizacion['productos'];

        $respuesta = $this->actualizarCotizacion($id, $cotizacionActualizada);

        $respuesta
            ->assertOk()
            ->assertExactJson([
                'cotizacion' => $this->getCotizacion($id),
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'La cotización ha sido modificada'
                ]
            ]);

        $cotizacionRespuesta = $respuesta->getData(true)['cotizacion'];

        $this->assertEquals($cotizacionActualizada['plazo'], $cotizacionRespuesta['plazo']);
        $this->assertEquals($cotizacionActualizada['razon_id'], $cotizacionRespuesta['razon_social']['id']);
        $this->assertEquals($cotizacionActualizada['estado_id'], $cotizacionRespuesta['estado']['id']);
        $this->assertEquals($cotizacionActualizada['consumidor_final'], $cotizacionRespuesta['consumidor_final']);
        $this->assertEquals($cotizacionActualizada['telefono_id'], $cotizacionRespuesta['telefono']['id']);
        $this->assertEquals($cotizacionActualizada['domicilio_id'], $cotizacionRespuesta['domicilio']['id']);
    }

    public function test_el_cliente_usuario_deberia_agregar_la_observacion_en_una_cotizacion()
    {
        $cotizacion = $this->crearCotizacionSinObservacion();
        $id = $cotizacion['id'];

        $observacion = factory(Observacion::class)->make()->toArray();
        $cotizacion['observacion'] = $observacion['descripcion'];

        $respuesta = $this->actualizarCotizacion($id, $cotizacion);

        $cotizacionRespuesta = $this->getCotizacion($id);

        $respuesta
            ->assertOk()
            ->assertExactJson([
                'cotizacion' => $cotizacionRespuesta,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'La cotización ha sido modificada'
                ]
            ]);

        $observacionRespuesta = $respuesta->getData(true)['cotizacion']['observacion'];
        $this->assertEquals($observacion['descripcion'], $observacionRespuesta['descripcion']);
    }

    public function test_el_cliente_usuario_deberia_actualizar_la_observacion_en_una_cotizacion()
    {
        $cotizacion = $this->crearCotizacion();
        $id = $cotizacion['id'];

        $observacion = factory(Observacion::class)->make()->toArray();
        $cotizacion['observacion'] = $observacion['descripcion'];

        $respuesta = $this->actualizarCotizacion($id, $cotizacion);

        $cotizacionRespuesta = $this->getCotizacion($id);

        $respuesta
            ->assertOk()
            ->assertExactJson([
                'cotizacion' => $cotizacionRespuesta,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'La cotización ha sido modificada'
                ]
            ]);

        $observacionRespuesta = $respuesta->getData(true)['cotizacion']['observacion'];
        $this->assertEquals($observacion['descripcion'], $observacionRespuesta['descripcion']);
    }

    public function test_el_cliente_usuario_deberia_actualizar_un_producto_en_una_cotizacion()
    {
        $cotizacion = $this->crearCotizacion();
        $id = $cotizacion['id'];

        $productoActualizado = $cotizacion['productos'][0];
        $productoActualizado['cantidad'] = 10;
        $productoActualizado['precio'] = 5000;
        $cotizacion['productos'][0] = $productoActualizado;

        $respuesta = $this->actualizarCotizacion($id, $cotizacion);

        $cotizacionRespuesta = $this->getCotizacion($id);

        $respuesta
            ->assertStatus(200)
            ->assertExactJson([
                'cotizacion' => $cotizacionRespuesta,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'La cotización ha sido modificada'
                ]
            ]);

        $productos = $respuesta->getData(true)['cotizacion']['productos'];

        foreach ($productos as $producto) {
            if ($productoActualizado['id'] === $producto['id']) {
                $this->assertEquals($productoActualizado['cantidad'], $producto['cantidad']);
                $this->assertEquals($productoActualizado['precio'], $producto['precio']);
            }
        }
    }

    public function test_el_cliente_usuario_deberia_agregar_un_producto_a_una_cotizacion()
    {
        $cotizacion = $this->crearCotizacion();
        $id = $cotizacion['id'];

        $nuevoProducto = factory(CotizacionProducto::class)
            ->make(['cotizacion_id' => $id])
            ->toArray();
        $producto = Producto::where('codigo', $nuevoProducto['codigo'])->first();
        $nuevoProducto['producto_id'] = $producto->id;
        $cotizacion['productos'][] = $nuevoProducto;

        $respuesta = $this->actualizarCotizacion($id, $cotizacion);

        $cotizacionRespuesta = $this->getCotizacion($id);

        $respuesta
            ->assertOk()
            ->assertExactJson([
                'cotizacion' => $cotizacionRespuesta,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'La cotización ha sido modificada'
                ]
            ]);

        $this->assertCount(6, $respuesta->getData(true)['cotizacion']['productos']);
    }

    public function test_el_cliente_usuario_deberia_agregar_un_producto_duplicado_en_una_cotizacion()
    {
        $cotizacion = $this->crearCotizacion();
        $id = $cotizacion['id'];

        $primerProducto = $cotizacion['productos'][0];
        $productoDuplicado = $primerProducto;
        $productoDuplicado['id'] = null;
        $cotizacion['productos'][] = $productoDuplicado;

        $respuesta = $this->actualizarCotizacion($id, $cotizacion);

        $cotizacionRespuesta = $this->getCotizacion($id);

        $respuesta
            ->assertOk()
            ->assertExactJson([
                'cotizacion' => $cotizacionRespuesta,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'La cotización ha sido modificada'
                ]
            ]);

        $this->assertCount(5, $respuesta->getData(true)['cotizacion']['productos']);

        $productos = $respuesta->getData(true)['cotizacion']['productos'];

        foreach ($productos as $producto) {
            if ($primerProducto['id'] === $producto['id']) {
                $cantidad = $primerProducto['cantidad'] + $productoDuplicado['cantidad'];

                $this->assertEquals($cantidad, $producto['cantidad']);
                $this->assertEquals($productoDuplicado['precio'], $producto['precio']);
            }
        }
    }

    public function test_el_cliente_usuario_deberia_eliminar_una_cotizacion()
    {
        $cotizacion = $this->crearCotizacion();
        $id = $cotizacion['id'];

        $respuesta = $this->eliminarCotizacion($id);

        $cotizacionRespuesta = $this->getCotizacion($id);

        $respuesta
            ->assertOk()
            ->assertJson([
                'cotizacion' => $cotizacionRespuesta,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ELIMINADO',
                    'descripcion' => 'La cotización ha sido eliminada'
                ]
            ]);
    }
}
