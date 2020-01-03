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

class ClienteTelefonoPolicyComoEmpleadoTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use ClienteTelefonoApi;

    protected $usuario;
    protected $cabeceras;
    protected $telefono;
    protected $clienteID;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoEmpleado();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->telefono = factory(ClienteTelefono::class)->create();
        $this->clienteID = $this->telefono->cliente_id;
    }

    /**
     * Ver teléfonos
     */

    public function test_el_empleado_puede_ver_los_telefonos_del_cliente()
    {
        $this->usuario->givePermissionTo('ver clientes');

        $respuesta = $this->obtenerTelefonos($this->clienteID);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_los_telefonos_del_cliente()
    {
        $respuesta = $this->obtenerTelefonos($this->clienteID);
        $respuesta->assertStatus(403);
    }

    /**
     * Ver teléfono
     */

    public function test_el_empleado_puede_ver_el_telefono_del_cliente()
    {
        $this->usuario->givePermissionTo('ver clientes');

        $respuesta = $this->obtenerTelefono($this->clienteID, $this->telefono->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_el_telefono_del_cliente()
    {
        $respuesta = $this->obtenerTelefono($this->clienteID, $this->telefono->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear teléfono
     */

    public function test_el_empleado_puede_crear_el_telefono_del_cliente()
    {
        $this->usuario->givePermissionTo('crear clientes');

        $respuesta = $this->crearTelefono($this->clienteID);
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_crear_el_telefono_del_cliente()
    {
        $respuesta = $this->crearTelefono($this->clienteID);
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar teléfono
     */

    public function test_el_empleado_puede_actualizar_el_telefono_del_cliente()
    {
        $this->usuario->givePermissionTo('actualizar clientes');

        $respuesta = $this->actualizarTelefono($this->clienteID, $this->telefono->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_actualizar_el_telefono_del_cliente()
    {
        $respuesta = $this->actualizarTelefono($this->clienteID, $this->telefono->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar teléfono
     */

    public function test_el_empleado_puede_eliminar_el_telefono_del_cliente()
    {
        $this->usuario->givePermissionTo('eliminar clientes');

        $respuesta = $this->eliminarTelefono($this->clienteID, $this->telefono->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_eliminar_el_telefono_del_cliente()
    {
        $respuesta = $this->eliminarTelefono($this->clienteID, $this->telefono->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar teléfono
     */

    public function test_el_empleado_puede_restaurar_el_telefono_del_cliente()
    {
        $this->usuario->givePermissionTo('eliminar clientes');

        $respuesta = $this->restaurarTelefono($this->clienteID, $this->telefono->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_puede_rno_estaurar_el_telefono_del_cliente()
    {
        $respuesta = $this->restaurarTelefono($this->clienteID, $this->telefono->id);
        $respuesta->assertStatus(403);
    }
}
