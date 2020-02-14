<?php

namespace Tests\Feature\app\Policies;

use App\Cotizacion;
use Tests\ApiTestCase;
use App\CotizacionProducto;
use CategoriaProductoSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\Api\CotizacionProductoApi;

class CotizacionProductoPolicyComoClienteTest extends ApiTestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use CotizacionProductoApi;

    protected $usuario;
    protected $cabeceras;
    protected $cotizacionProductoAsociado;
    protected $cotizacionProductoNoAsociado;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(CategoriaProductoSeeder::class);

        $login = $this->loguearseComoCliente();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->cotizacionProductoAsociado = $this->crearCotizacionProductoAsociado();
        $this->cotizacionProductoNoAsociado = $this->crearCotizacionProductoNoAsociado();
    }

    private function crearCotizacionProductoAsociado(): CotizacionProducto
    {
        $cotizacion = factory(Cotizacion::class)->create([
            'cliente_id' => $this->usuario->id_cliente
        ]);

        $cotizacionProducto = factory(CotizacionProducto::class)->create([
            'cotizacion_id' => $cotizacion->id
        ]);

        $cotizacionProducto->producto_id = $cotizacionProducto->producto->id;

        return $cotizacionProducto;
    }

    private function crearCotizacionProductoNoAsociado(): CotizacionProducto
    {
        $cotizacion = factory(Cotizacion::class)->create();

        $cotizacionProducto = factory(CotizacionProducto::class)->create([
            'cotizacion_id' => $cotizacion->id
        ]);

        return $cotizacionProducto;
    }

    /**
     * Actualizar producto de la cotizacion
     */

    public function test_el_cliente_usuario_puede_actualizar_un_producto_de_la_cotizacion()
    {
        $respuesta = $this->actualizarCotizacionProductos(
            $this->cotizacionProductoAsociado->cotizacion_id,
            [$this->cotizacionProductoAsociado]
        );
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_actualizar_un_producto_de_la_cotizacion()
    {
        $respuesta = $respuesta = $this->actualizarCotizacionProductos(
            $this->cotizacionProductoNoAsociado->cotizacion_id,
            [$this->cotizacionProductoNoAsociado]
        );
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar producto de la cotizacion
     */

    public function test_el_cliente_usuario_puede_eliminar_un_producto_de_la_cotizacion()
    {
        $respuesta = $this->eliminarCotizacionProducto($this->cotizacionProductoAsociado->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_eliminar_un_producto_de_la_cotizacion()
    {
        $respuesta = $this->eliminarCotizacionProducto($this->cotizacionProductoNoAsociado->id);
        $respuesta->assertStatus(403);
    }
}
