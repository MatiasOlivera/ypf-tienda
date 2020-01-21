<?php

namespace Tests\Feature\app\Policies;

use Tests\TestCase;
use App\ClienteUsuario;
use AutorizacionSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\Api\ClienteUsuarioApi;

class ClienteUsuarioPolicyComoEmpleadoTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use ClienteUsuarioApi;

    protected $usuario;
    protected $cabeceras;
    protected $clienteUsuario;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoEmpleado();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->clienteUsuario = factory(ClienteUsuario::class)->create();
    }

    /**
     * Ver usuarios de los clientes
     */

    public function test_el_empleado_puede_ver_los_usuarios_de_los_clientes()
    {
        $this->usuario->givePermissionTo('ver usuarios de clientes');

        $respuesta = $this->obtenerClienteUsuarios();
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_los_usuarios_de_los_clientes()
    {
        $respuesta = $this->obtenerClienteUsuarios();
        $respuesta->assertStatus(403);
    }

    /**
     * Ver cliente usuario
     */

    public function test_el_empleado_puede_ver_el_cliente_usuario()
    {
        $this->usuario->givePermissionTo('ver usuarios de clientes');

        $respuesta = $this->obtenerClienteUsuario($this->clienteUsuario->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_el_cliente_usuario()
    {
        $respuesta = $this->obtenerClienteUsuario($this->clienteUsuario->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear cliente usuario
     */

    public function test_el_empleado_puede_crear_el_cliente_usuario()
    {
        $this->usuario->givePermissionTo('crear usuarios de clientes');

        $respuesta = $this->crearClienteUsuario();
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_crear_el_cliente_usuario()
    {
        $respuesta = $this->crearClienteUsuario();
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar cliente usuario
     */

    public function test_el_empleado_puede_actualizar_el_cliente_usuario()
    {
        $this->usuario->givePermissionTo('actualizar usuarios de clientes');

        $respuesta = $this->actualizarClienteUsuario($this->clienteUsuario->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_actualizar_el_cliente_usuario()
    {
        $respuesta = $this->actualizarClienteUsuario($this->clienteUsuario->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar cliente usuario
     */

    public function test_el_empleado_puede_eliminar_el_cliente_usuario()
    {
        $this->usuario->givePermissionTo('eliminar usuarios de clientes');

        $respuesta = $this->eliminarClienteUsuario($this->clienteUsuario->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_eliminar_el_cliente_usuario()
    {
        $respuesta = $this->eliminarClienteUsuario($this->clienteUsuario->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar cliente usuario
     */

    public function test_el_empleado_puede_restaurar_el_cliente_usuario()
    {
        $this->usuario->givePermissionTo('eliminar usuarios de clientes');

        $respuesta = $this->restaurarClienteUsuario($this->clienteUsuario->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_restaurar_el_cliente_usuario()
    {
        $respuesta = $this->restaurarClienteUsuario($this->clienteUsuario->id);
        $respuesta->assertStatus(403);
    }
}
