<?php

namespace Tests\Feature\app\Policies;

use App\Cotizacion;
use Tests\ApiTestCase;
use AutorizacionSeeder;
use CategoriaProductoSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\Api\CotizacionApi;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CotizacionPolicyComoEmpleadoTest extends ApiTestCase
{
    use AuthHelper;
    use CotizacionApi;
    use RefreshDatabase;
    use EloquenceSolucion;

    protected $usuario;
    protected $cabeceras;
    protected $cotizacion;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(CategoriaProductoSeeder::class);
        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoEmpleado();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->cotizacion = factory(Cotizacion::class)->create();
    }

    /**
     * Ver cotizaciones
     */

    public function test_el_empleado_puede_ver_todas_las_cotizaciones()
    {
        $this->usuario->givePermissionTo('ver cotizaciones');

        $respuesta = $this->obtenerCotizaciones();
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_todas_las_cotizaciones()
    {
        $respuesta = $this->obtenerCotizaciones();
        $respuesta->assertStatus(403);
    }

    public function test_el_empleado_puede_ver_las_cotizaciones_de_un_cliente()
    {
        $this->usuario->givePermissionTo('ver cotizaciones');

        $respuesta = $this->obtenerCotizacionesDelCliente($this->cotizacion->cliente->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_las_cotizaciones_de_un_cliente()
    {
        $respuesta = $this->obtenerCotizacionesDelCliente($this->cotizacion->cliente->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Ver cotizacion
     */

    public function test_el_empleado_puede_ver_la_cotizacion()
    {
        $this->usuario->givePermissionTo('ver cotizaciones');

        $respuesta = $this->obtenerCotizacion($this->cotizacion->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_la_cotizacion()
    {
        $respuesta = $this->obtenerCotizacion($this->cotizacion->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear cotizacion
     */

    public function test_el_empleado_puede_crear_una_cotizacion()
    {
        $this->usuario->givePermissionTo('crear cotizaciones');

        $respuesta = $this->crearCotizacionDelCliente($this->cotizacion->cliente->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_crear_una_cotizacion()
    {
        $respuesta = $this->crearCotizacionDelCliente($this->cotizacion->cliente->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar cotizacion
     */

    public function test_el_empleado_puede_actualizar_una_cotizacion()
    {
        $this->usuario->givePermissionTo('actualizar cotizaciones');

        $respuesta = $this->actualizarCotizacion($this->cotizacion->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_actualizar_una_cotizacion()
    {
        $respuesta = $this->actualizarCotizacion($this->cotizacion->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar cotizacion
     */

    public function test_el_empleado_puede_eliminar_una_cotizacion()
    {
        $this->usuario->givePermissionTo('eliminar cotizaciones');

        $respuesta = $this->eliminarCotizacion($this->cotizacion->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_eliminar_una_cotizacion()
    {
        $respuesta = $this->eliminarCotizacion($this->cotizacion->id);
        $respuesta->assertStatus(403);
    }
}
