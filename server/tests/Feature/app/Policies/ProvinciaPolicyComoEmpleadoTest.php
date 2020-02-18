<?php

namespace Tests\Feature\app\Policies;

use App\Provincia;
use Tests\ApiTestCase;
use AutorizacionSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\Api\ProvinciaApi;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProvinciaPolicyComoEmpleadoTest extends ApiTestCase
{
    use AuthHelper;
    use ProvinciaApi;
    use RefreshDatabase;
    use EloquenceSolucion;

    protected $usuario;
    protected $cabeceras;
    protected $provincia;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoEmpleado();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->provincia = factory(Provincia::class)->create();
    }

    /**
     * Ver provincias
     */

    public function test_el_empleado_puede_ver_provincias()
    {
        $this->usuario->givePermissionTo('ver provincias');

        $respuesta = $this->obtenerProvincias();
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_provincias()
    {
        $respuesta = $this->obtenerProvincias();
        $respuesta->assertStatus(403);
    }

    /**
     * Ver provincia
     */

    public function test_el_empleado_puede_ver_una_provincia()
    {
        $this->usuario->givePermissionTo('ver provincias');

        $respuesta = $this->obtenerProvincia($this->provincia->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_una_provincia()
    {
        $respuesta = $this->obtenerProvincia($this->provincia->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear provincia
     */

    public function test_el_empleado_puede_crear_provincias()
    {
        $this->usuario->givePermissionTo('crear provincias');

        $respuesta = $this->crearProvincia();
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_crear_provincias()
    {
        $respuesta = $this->crearProvincia();
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar provincia
     */

    public function test_el_empleado_puede_actualizar_provincias()
    {
        $this->usuario->givePermissionTo('actualizar provincias');

        $respuesta = $this->actualizarProvincia($this->provincia->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_actualizar_provincias()
    {
        $respuesta = $this->actualizarProvincia($this->provincia->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar provincia
     */

    public function test_el_empleado_puede_eliminar_provincias()
    {
        $this->usuario->givePermissionTo('eliminar provincias');

        $respuesta = $this->eliminarProvincia($this->provincia->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_eliminar_provincias()
    {
        $respuesta = $this->eliminarProvincia($this->provincia->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar provincia
     */

    public function test_el_empleado_puede_restaurar_provincias()
    {
        $this->usuario->givePermissionTo('eliminar provincias');

        $respuesta = $this->restaurarProvincia($this->provincia->id);
        $respuesta->assertOk();
     }

    public function test_el_empleado_no_puede_restaurar_provincias()
    {
        $respuesta = $this->restaurarProvincia($this->provincia->id);
        $respuesta->assertStatus(403);
    }
}
