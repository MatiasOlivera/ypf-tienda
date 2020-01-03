<?php

namespace Tests\Feature\app\Policies;

use App\Cliente;
use Tests\TestCase;
use AutorizacionSeeder;
use App\ClienteTelefono;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\Api\ClienteTelefonoApi;

class ClienteTelefonoPolicyComoClienteTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use ClienteTelefonoApi;

    protected $usuario;
    protected $cabeceras;
    protected $clienteID;
    protected $telefonoAsociado;
    protected $telefonoNoAsociado;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoCliente();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];
        $this->clienteID = $this->usuario->id_cliente;

        $this->telefonoAsociado = factory(ClienteTelefono::class)->create([
            'cliente_id' => $this->clienteID
        ]);
        $this->telefonoNoAsociado = factory(ClienteTelefono::class)->create();
    }

    /**
     * Ver teléfonos
     */

    public function test_el_cliente_usuario_puede_ver_los_telefonos_del_cliente_asociado()
    {
        $respuesta = $this->obtenerTelefonos($this->clienteID);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_ver_los_telefonos_de_un_cliente_no_asociado()
    {
        $cliente = factory(Cliente::class)->create();
        $respuesta = $this->obtenerTelefonos($cliente->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Ver teléfono
     */

    public function test_el_cliente_usuario_puede_ver_el_telefono_del_cliente_asociado()
    {
        $respuesta = $this->obtenerTelefono($this->clienteID, $this->telefonoAsociado->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_ver_el_telefono_de_un_cliente_no_asociado()
    {
        $clienteID = $this->telefonoNoAsociado->cliente_id;
        $telefonoID = $this->telefonoNoAsociado->id;
        $respuesta = $this->obtenerTelefono($clienteID, $telefonoID);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear teléfono
     */

    public function test_el_cliente_usuario_puede_crear_un_telefono_del_cliente_asociado()
    {
        $respuesta = $this->crearTelefono($this->clienteID);
        $respuesta->assertStatus(422);
    }

    public function test_el_cliente_usuario_no_puede_crear_un_telefono_a_un_cliente_no_asociado()
    {
        $cliente = factory(Cliente::class)->create();
        $respuesta = $this->crearTelefono($cliente->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar teléfono
     */

    public function test_el_cliente_usuario_puede_actualizar_un_telefono_del_cliente_asociado()
    {
        $respuesta = $this->actualizarTelefono($this->clienteID, $this->telefonoAsociado->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_cliente_usuario_no_puede_actualizar_un_telefono_de_un_cliente_no_asociado()
    {
        $clienteID = $this->telefonoNoAsociado->cliente_id;
        $telefonoID = $this->telefonoNoAsociado->id;
        $respuesta = $this->actualizarTelefono($clienteID, $telefonoID);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar teléfono
     */

    public function test_el_cliente_usuario_puede_eliminar_un_telefono_del_cliente_asociado()
    {
        $respuesta = $this->eliminarTelefono($this->clienteID, $this->telefonoAsociado->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_eliminar_un_telefono_de_un_cliente_no_asociado()
    {
        $clienteID = $this->telefonoNoAsociado->cliente_id;
        $telefonoID = $this->telefonoNoAsociado->id;
        $respuesta = $this->eliminarTelefono($clienteID, $telefonoID);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar teléfono
     */

    public function test_el_cliente_usuario_puede_restaurar_un_telefono_del_cliente_asociado()
    {
        $respuesta = $this->restaurarTelefono($this->clienteID, $this->telefonoAsociado->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_restaurar_un_telefono_de_un_cliente_no_asociado()
    {
        $clienteID = $this->telefonoNoAsociado->cliente_id;
        $telefonoID = $this->telefonoNoAsociado->id;
        $respuesta = $this->restaurarTelefono($clienteID, $telefonoID);
        $respuesta->assertStatus(403);
    }
}
