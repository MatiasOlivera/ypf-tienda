<?php

namespace Tests\Feature\app\Policies;

use App\Localidad;
use Tests\ApiTestCase;
use AutorizacionSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\Api\LocalidadApi;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocalidadPolicyComoEmpleadoTest extends ApiTestCase
{
    use AuthHelper;
    use LocalidadApi;
    use RefreshDatabase;
    use EloquenceSolucion;

    protected $usuario;
    protected $cabeceras;
    protected $localidad;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoEmpleado();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->localidad = factory(Localidad::class)->create();
    }

    /**
     * Ver localidades
     */

    public function test_el_empleado_puede_ver_localidades()
    {
        $this->usuario->givePermissionTo('ver localidades');

        $respuesta = $this->obtenerLocalidades($this->localidad->id_provincia);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_localidades()
    {
        $respuesta = $this->obtenerLocalidades($this->localidad->id_provincia);
        $respuesta->assertStatus(403);
    }

    /**
     * Ver localidad
     */

    public function test_el_empleado_puede_ver_una_localidad()
    {
        $this->usuario->givePermissionTo('ver localidades');

        $respuesta = $this->obtenerLocalidad($this->localidad->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_una_localidad()
    {
        $respuesta = $this->obtenerLocalidad($this->localidad->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear localidad
     */

    public function test_el_empleado_puede_crear_localidades()
    {
        $this->usuario->givePermissionTo('crear localidades');

        $respuesta = $this->crearLocalidad();
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_crear_localidades()
    {
        $respuesta = $this->crearLocalidad();
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar localidad
     */

    public function test_el_empleado_puede_actualizar_localidades()
    {
        $this->usuario->givePermissionTo('actualizar localidades');

        $respuesta = $this->actualizarLocalidad($this->localidad->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_actualizar_localidades()
    {
        $respuesta = $this->actualizarLocalidad($this->localidad->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar localidad
     */

    public function test_el_empleado_puede_eliminar_localidades()
    {
        $this->usuario->givePermissionTo('eliminar localidades');

        $respuesta = $this->eliminarLocalidad($this->localidad->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_eliminar_localidades()
    {
        $respuesta = $this->eliminarLocalidad($this->localidad->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar localidad
     */

    public function test_el_empleado_puede_restaurar_localidades()
    {
        $this->usuario->givePermissionTo('eliminar localidades');

        $respuesta = $this->restaurarLocalidad($this->localidad->id);
        $respuesta->assertOk();
     }

    public function test_el_empleado_no_puede_restaurar_localidades()
    {
        $respuesta = $this->restaurarLocalidad($this->localidad->id);
        $respuesta->assertStatus(403);
    }
}
