<?php

namespace Tests\Feature\app\Policies;

use Tests\ApiTestCase;
use App\ClienteUsuario;
use AutorizacionSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\Api\ClienteUsuarioApi;

class ClienteUsuarioPolicyComoClienteTest extends ApiTestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use ClienteUsuarioApi;

    protected $usuario;
    protected $cabeceras;
    protected $otroUsuario;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoCliente();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->otroUsuario = factory(ClienteUsuario::class)->create();
    }

    /**
     * Ver usuarios de los clientes
     */

    public function test_el_cliente_usuario_no_puede_ver_los_usuarios_de_los_clientes()
    {
        $respuesta = $this->obtenerClienteUsuarios();
        $respuesta->assertStatus(403);
    }

    /**
     * Ver cliente usuario
     */

    public function test_el_cliente_usuario_puede_ver_sus_datos()
    {
        $respuesta = $this->obtenerClienteUsuario($this->usuario->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_ver_los_datos_de_otro_cliente_usuario()
    {
        $respuesta = $this->obtenerClienteUsuario($this->otroUsuario->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear cliente usuario
     */

    public function test_el_cliente_usuario_no_puede_crear_un_cliente_usuario()
    {
        $respuesta = $this->crearClienteUsuario();
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar cliente usuario
     */

    public function test_el_cliente_usuario_puede_actualizar_sus_datos()
    {
        $respuesta = $this->actualizarClienteUsuario($this->usuario->id, []);
        $respuesta->assertStatus(422);
    }

    public function test_el_cliente_usuario_no_puede_actualizar_los_datos_de_otro_cliente_usuario()
    {
        $respuesta = $this->actualizarClienteUsuario($this->otroUsuario->id, []);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar cliente usuario
     */

    public function test_el_cliente_usuario_no_puede_eliminar_un_cliente_usuario()
    {
        $respuesta = $this->eliminarClienteUsuario($this->usuario->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar cliente usuario
     */

    public function test_el_cliente_usuario_no_puede_restaurar_un_cliente_usuario()
    {
        $respuesta = $this->restaurarClienteUsuario($this->usuario->id);
        $respuesta->assertStatus(403);
    }
}
