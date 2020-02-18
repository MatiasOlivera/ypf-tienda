<?php

namespace Tests\Feature\app\Policies;

use App\Cliente;
use Tests\ApiTestCase;
use AutorizacionSeeder;
use App\ClienteDomicilio;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\Api\ClienteDomicilioApi;

class ClienteDomicilioPolicyComoEmpleadoTest extends ApiTestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use ClienteDomicilioApi;

    protected $usuario;
    protected $cabeceras;
    protected $domicilio;
    protected $clienteID;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoEmpleado();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->domicilio = factory(ClienteDomicilio::class)->create();
        $this->clienteID = $this->domicilio->id_cliente;
    }

    /**
     * Ver domicilios
     */

    public function test_el_empleado_puede_ver_los_domicilios_del_cliente()
    {
        $this->usuario->givePermissionTo('ver clientes');

        $respuesta = $this->obtenerDomicilios($this->clienteID);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_los_domicilios_del_cliente()
    {
        $respuesta = $this->obtenerDomicilios($this->clienteID);
        $respuesta->assertStatus(403);
    }

    /**
     * Ver domicilio
     */

    public function test_el_empleado_puede_ver_el_domicilio_del_cliente()
    {
        $this->usuario->givePermissionTo('ver clientes');

        $respuesta = $this->obtenerDomicilio($this->clienteID, $this->domicilio->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_el_domicilio_del_cliente()
    {
        $respuesta = $this->obtenerDomicilio($this->clienteID, $this->domicilio->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear domicilio
     */

    public function test_el_empleado_puede_crear_el_domicilio_del_cliente()
    {
        $this->usuario->givePermissionTo('crear clientes');

        $respuesta = $this->crearDomicilio($this->clienteID);
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_crear_el_domicilio_del_cliente()
    {
        $respuesta = $this->crearDomicilio($this->clienteID);
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar domicilio
     */

    public function test_el_empleado_puede_actualizar_el_domicilio_del_cliente()
    {
        $this->usuario->givePermissionTo('actualizar clientes');

        $respuesta = $this->actualizarDomicilio($this->clienteID, $this->domicilio->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_actualizar_el_domicilio_del_cliente()
    {
        $respuesta = $this->actualizarDomicilio($this->clienteID, $this->domicilio->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar domicilio
     */

    public function test_el_empleado_puede_eliminar_el_domicilio_del_cliente()
    {
        $this->usuario->givePermissionTo('eliminar clientes');

        $respuesta = $this->eliminarDomicilio($this->clienteID, $this->domicilio->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_eliminar_el_domicilio_del_cliente()
    {
        $respuesta = $this->eliminarDomicilio($this->clienteID, $this->domicilio->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar domicilio
     */

    public function test_el_empleado_puede_restaurar_el_domicilio_del_cliente()
    {
        $this->usuario->givePermissionTo('eliminar clientes');

        $respuesta = $this->restaurarDomicilio($this->clienteID, $this->domicilio->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_puede_rno_estaurar_el_domicilio_del_cliente()
    {
        $respuesta = $this->restaurarDomicilio($this->clienteID, $this->domicilio->id);
        $respuesta->assertStatus(403);
    }
}
