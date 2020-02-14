<?php

namespace Tests\Feature\app\Policies;

use App\Cotizacion;
use Tests\ApiTestCase;
use AutorizacionSeeder;
use App\CotizacionProducto;
use CategoriaProductoSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\Api\CotizacionProductoApi;

class CotizacionProductoPolicyComoEmpleadoTest extends ApiTestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use CotizacionProductoApi;

    protected $usuario;
    protected $cabeceras;
    protected $cotizacionProducto;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(CategoriaProductoSeeder::class);
        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoEmpleado();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->cotizacionProducto = $this->crearCotizacionProductoAsociado();
    }

    private function crearCotizacionProductoAsociado(): CotizacionProducto
    {
        $cotizacion = factory(Cotizacion::class)->create();

        $cotizacionProducto = factory(CotizacionProducto::class)->create([
            'cotizacion_id' => $cotizacion->id
        ]);

        $cotizacionProducto->producto_id = $cotizacionProducto->producto->id;

        return $cotizacionProducto;
    }

    /**
     * Actualizar producto de la cotizacion
     */

    public function test_el_empleado_puede_actualizar_un_producto_de_la_cotizacion()
    {
        $this->usuario->givePermissionTo('actualizar cotizaciones');

        $respuesta = $this->actualizarCotizacionProductos(
            $this->cotizacionProducto->cotizacion_id,
            [$this->cotizacionProducto]
        );
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_actualizar_un_producto_de_la_cotizacion()
    {
        $respuesta = $respuesta = $this->actualizarCotizacionProductos(
            $this->cotizacionProducto->cotizacion_id,
            [$this->cotizacionProducto]
        );
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar producto de la cotizacion
     */

    public function test_el_empleado_puede_eliminar_un_producto_de_la_cotizacion()
    {
        $this->usuario->givePermissionTo('eliminar cotizaciones');

        $respuesta = $this->eliminarCotizacionProducto($this->cotizacionProducto->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_eliminar_un_producto_de_la_cotizacion()
    {
        $respuesta = $this->eliminarCotizacionProducto($this->cotizacionProducto->id);
        $respuesta->assertStatus(403);
    }
}
