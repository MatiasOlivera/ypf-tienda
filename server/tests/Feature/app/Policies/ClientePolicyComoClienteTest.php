<?php

namespace Tests\Feature\app\Policies;

use App\Cliente;
use Tests\TestCase;
use AutorizacionSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientePolicyComoClienteTest extends TestCase
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

        $login = $this->loguearseComoCliente();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];
    }

    /**
     * Ver clientes
     */

    public function test_el_cliente_usuario_no_puede_ver_clientes()
    {
        $respuesta = $this
            ->withHeaders($this->cabeceras)
            ->json('GET', 'api/clientes');
        $respuesta->assertStatus(403);
    }

    /**
     * Ver cliente
     */

    public function test_el_cliente_usuario_puede_ver_el_cliente_asociado()
    {
        $respuesta = $this->obtenerCliente($this->usuario->id_cliente);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_ver_el_cliente()
    {
        $cliente = factory(Cliente::class)->create();

        $respuesta = $this->obtenerCliente($cliente->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear clientes
     */

    public function test_el_cliente_usuario_no_puede_crear_un_cliente()
    {
        $respuesta = $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes", []);
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar clientes
     */

    public function test_el_cliente_usuario_puede_actualizar_el_cliente_asociado()
    {
        $respuesta = $this->actualizarCliente($this->usuario->id_cliente);
        $respuesta->assertStatus(422);
    }

    public function test_el_cliente_usuario_no_puede_actualizar_el_cliente()
    {
        $cliente = factory(Cliente::class)->create();

        $respuesta = $this->actualizarCliente($cliente->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar clientes
     */

    public function test_el_cliente_usuario_no_puede_eliminar_un_cliente()
    {
        $id = $this->usuario->id_cliente;

        $respuesta = $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/clientes/$id");
        $respuesta->assertStatus(403);
    }

    public function test_el_cliente_usuario_no_puede_restaurar_un_cliente()
    {
        $id = $this->usuario->id_cliente;

        $respuesta = $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes/$id/restaurar");
        $respuesta->assertStatus(403);
    }

    protected function obtenerCliente($id)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/$id");
    }

    protected function actualizarCliente($id)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/clientes/$id", []);
    }
}
