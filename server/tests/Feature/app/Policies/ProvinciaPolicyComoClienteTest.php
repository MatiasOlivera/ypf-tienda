<?php

namespace Tests\Feature\app\Policies;

use App\Provincia;
use Tests\ApiTestCase;
use AutorizacionSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\Api\ProvinciaApi;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProvinciaPolicyComoClienteTest extends ApiTestCase
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

        $login = $this->loguearseComoCliente();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->provincia = factory(Provincia::class)->create();
    }

    /**
     * Ver provincias
     */

    public function test_el_cliente_usuario_puede_ver_las_provincias()
    {
        $respuesta = $this->obtenerProvincias();
        $respuesta->assertOk();
    }

    /**
     * Ver provincia
     */

    public function test_el_cliente_usuario_puede_ver_la_provincia()
    {
        $respuesta = $this->obtenerProvincia($this->provincia->id);
        $respuesta->assertOk();
    }

    /**
     * Crear provincia
     */

    public function test_el_cliente_usuario_no_puede_crear_una_provincia()
    {
        $respuesta = $this->crearProvincia();
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar provincia
     */

    public function test_el_cliente_usuario_no_puede_actualizar_una_provincia()
    {
        $respuesta = $this->actualizarProvincia($this->provincia->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar provincia
     */

    public function test_el_cliente_usuario_no_puede_eliminar_una_provincia()
    {
        $respuesta = $this->eliminarProvincia($this->provincia->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar provincia
     */

    public function test_el_cliente_usuario_no_puede_restaurar_una_provincia()
    {
        $respuesta = $this->restaurarProvincia($this->provincia->id);
        $respuesta->assertStatus(403);
    }
}
