<?php

namespace Tests\Feature\app\Policies;

use App\Cliente;
use Tests\TestCase;
use AutorizacionSeeder;
use App\ClienteRazonSocial;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\Api\ClienteRazonSocialApi;

class ClienteRazonSocialPolicyComoEmpleadoTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use ClienteRazonSocialApi;

    protected $usuario;
    protected $cabeceras;
    protected $razonSocial;
    protected $clienteID;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoEmpleado();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->cliente = factory(Cliente::class)->create();
        $this->razonSocial = factory(ClienteRazonSocial::class)->create();
        $this->clienteID = $this->cliente->id;

        $loginComoSuperAdmin = $this->loguearseComoSuperAdministrador();
        $this->asociarClienteRazonSocial(
            $loginComoSuperAdmin['cabeceras'],
            $this->clienteID,
            $this->razonSocial->id
        );
    }

    /**
     * Ver razón sociales
     */

    public function test_el_empleado_puede_ver_las_razones_sociales_del_cliente()
    {
        $this->usuario->givePermissionTo('ver clientes');

        $respuesta = $this->obtenerRazonesSociales($this->clienteID);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_las_razones_sociales_del_cliente()
    {
        $respuesta = $this->obtenerRazonesSociales($this->clienteID);
        $respuesta->assertStatus(403);
    }

    /**
     * Ver razón social
     */

    public function test_el_empleado_puede_ver_la_razon_social_del_cliente()
    {
        $this->usuario->givePermissionTo('ver clientes');

        $respuesta = $this->obtenerRazonSocial($this->clienteID, $this->razonSocial->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_la_razon_social_del_cliente()
    {
        $respuesta = $this->obtenerRazonSocial($this->clienteID, $this->razonSocial->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear razón social
     */

    public function test_el_empleado_puede_crear_la_razon_social_del_cliente()
    {
        $this->usuario->givePermissionTo('crear clientes');

        $respuesta = $this->crearRazonSocial($this->clienteID);
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_crear_la_razon_social_del_cliente()
    {
        $respuesta = $this->crearRazonSocial($this->clienteID);
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar razón social
     */

    public function test_el_empleado_puede_actualizar_la_razon_social_del_cliente()
    {
        $this->usuario->givePermissionTo('actualizar clientes');

        $respuesta = $this->actualizarRazonSocial($this->clienteID, $this->razonSocial->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_actualizar_la_razon_social_del_cliente()
    {
        $respuesta = $this->actualizarRazonSocial($this->clienteID, $this->razonSocial->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar razón social
     */

    public function test_el_empleado_puede_eliminar_la_razon_social_del_cliente()
    {
        $this->usuario->givePermissionTo('eliminar clientes');

        $respuesta = $this->eliminarRazonSocial($this->clienteID, $this->razonSocial->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_eliminar_la_razon_social_del_cliente()
    {
        $respuesta = $this->eliminarRazonSocial($this->clienteID, $this->razonSocial->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar razón social
     */

    public function test_el_empleado_puede_restaurar_la_razon_social_del_cliente()
    {
        $this->usuario->givePermissionTo('eliminar clientes');

        $respuesta = $this->restaurarRazonSocial($this->clienteID, $this->razonSocial->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_restaurar_la_razon_social_del_cliente()
    {
        $respuesta = $this->restaurarRazonSocial($this->clienteID, $this->razonSocial->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Asociar el cliente con la razón
     */

    public function test_el_empleado_puede_asociar_el_cliente_y_la_razon()
    {
        $cliente = factory(Cliente::class)->create();
        $razonSocial = factory(ClienteRazonSocial::class)->create();

        $this->usuario->givePermissionTo('actualizar clientes');

        $respuesta = $this->asociarClienteRazonSocial(
            $this->cabeceras,
            $cliente->id,
            $razonSocial->id
        );
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_asociar_el_cliente_y_la_razon()
    {
        $cliente = factory(Cliente::class)->create();
        $razonSocial = factory(ClienteRazonSocial::class)->create();

        $respuesta = $this->asociarClienteRazonSocial(
            $this->cabeceras,
            $cliente->id,
            $razonSocial->id
        );
        $respuesta->assertStatus(403);
    }

    /**
     * Desasociar el cliente y la razón
     */
    public function test_el_empleado_puede_desasociar_el_cliente_y_la_razon()
    {
        $cliente = factory(Cliente::class)->create();
        $razonSocial = factory(ClienteRazonSocial::class)->create();

        $this->usuario->givePermissionTo('actualizar clientes');

        $respuesta = $this->desasociarClienteRazonSocial(
            $this->cabeceras,
            $cliente->id,
            $razonSocial->id
        );
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_desasociar_el_cliente_y_la_razon()
    {
        $cliente = factory(Cliente::class)->create();
        $razonSocial = factory(ClienteRazonSocial::class)->create();

        $respuesta = $this->desasociarClienteRazonSocial(
            $this->cabeceras,
            $cliente->id,
            $razonSocial->id
        );
        $respuesta->assertStatus(403);
    }
}
