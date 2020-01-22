<?php

namespace Tests\Feature\app\Policies;

use App\Localidad;
use Tests\ApiTestCase;
use AutorizacionSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\Api\LocalidadApi;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocalidadPolicyComoClienteTest extends ApiTestCase
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

        $login = $this->loguearseComoCliente();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->localidad = factory(Localidad::class)->create();
    }

    /**
     * Ver localidades
     */

    public function test_el_cliente_usuario_puede_ver_las_localidades()
    {
        $respuesta = $this->obtenerLocalidades($this->localidad->id_provincia);
        $respuesta->assertOk();
    }

    /**
     * Ver localidad
     */

    public function test_el_cliente_usuario_puede_ver_la_localidad()
    {
        $respuesta = $this->obtenerLocalidad($this->localidad->id);
        $respuesta->assertOk();
    }

    /**
     * Crear localidad
     */

    public function test_el_cliente_usuario_no_puede_crear_una_localidad()
    {
        $respuesta = $this->crearLocalidad();
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar localidad
     */

    public function test_el_cliente_usuario_no_puede_actualizar_una_localidad()
    {
        $respuesta = $this->actualizarLocalidad($this->localidad->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar localidad
     */

    public function test_el_cliente_usuario_no_puede_eliminar_una_localidad()
    {
        $respuesta = $this->eliminarLocalidad($this->localidad->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar localidad
     */

    public function test_el_cliente_usuario_no_puede_restaurar_una_localidad()
    {
        $respuesta = $this->restaurarLocalidad($this->localidad->id);
        $respuesta->assertStatus(403);
    }
}
