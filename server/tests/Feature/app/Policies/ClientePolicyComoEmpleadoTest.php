<?php

namespace Tests\Feature\app\Policies;

use App\Cliente;
use Tests\TestCase;
use AutorizacionSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientePolicyComoEmpleadoTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;

    protected $usuario;
    protected $cabeceras;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoEmpleado();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->cliente = factory(Cliente::class)->create();
    }

    /**
     * Ver clientes
     */

    public function test_el_empleado_puede_ver_clientes()
    {
        $this->usuario->givePermissionTo('ver clientes');

        $respuesta = $this->obtenerClientes();
        $respuesta->assertStatus(200);
    }

    public function test_el_empleado_no_puede_ver_clientes()
    {
        $respuesta = $this->obtenerClientes();
        $respuesta->assertStatus(403);
    }

    /**
     * Ver cliente
     */

    public function test_el_empleado_puede_ver_cliente()
    {
        $this->usuario->givePermissionTo('ver clientes');

        $respuesta = $this->obtenerCliente();
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_cliente()
    {
        $respuesta = $this->obtenerCliente();
        $respuesta->assertStatus(403);
    }

    /**
     * Crear clientes
     */

    public function test_el_empleado_puede_crear_clientes()
    {
        $this->usuario->givePermissionTo('crear clientes');

        $respuesta = $this->crearCliente();
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_crear_clientes()
    {
        $respuesta = $this->crearCliente();
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar clientes
     */

    public function test_el_empleado_puede_actualizar_clientes()
    {
        $this->usuario->givePermissionTo('actualizar clientes');

        $respuesta = $this->actualizarCliente();
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_actualizar_clientes()
    {
        $respuesta = $this->actualizarCliente();
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar clientes
     */

    public function test_el_empleado_puede_eliminar_clientes()
    {
        $this->usuario->givePermissionTo('eliminar clientes');

        $respuesta = $this->eliminarCliente();
        $respuesta->assertStatus(200);
    }

    public function test_el_empleado_no_puede_eliminar_clientes()
    {
        $respuesta = $this->eliminarCliente();
        $respuesta->assertStatus(403);
    }

    public function test_el_empleado_puede_restaurar_clientes()
    {
        $this->usuario->givePermissionTo('eliminar clientes');

        $respuesta = $this->restaurarCliente();
        $respuesta->assertStatus(200);
     }

    public function test_el_empleado_no_puede_restaurar_clientes()
    {
        $respuesta = $this->restaurarCliente();
        $respuesta->assertStatus(403);
    }

    protected function obtenerClientes()
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', 'api/clientes');
    }

    protected function obtenerCliente()
    {
        $id = $this->cliente->id;

        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/$id");
    }

    protected function crearCliente()
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', 'api/clientes', []);
    }

    protected function actualizarCliente()
    {
        $id = $this->cliente->id;

        return $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/clientes/$id", []);
    }

    protected function eliminarCliente()
    {
        $id = $this->cliente->id;

        return  $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/clientes/$id");
    }

    protected function restaurarCliente()
    {
        $id = $this->cliente->id;

        $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/clientes/$id");

        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes/$id/restaurar");
    }
}
