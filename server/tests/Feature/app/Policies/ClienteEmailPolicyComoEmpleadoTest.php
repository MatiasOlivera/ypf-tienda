<?php

namespace Tests\Feature\app\Policies;

use App\Cliente;
use Tests\ApiTestCase;
use AutorizacionSeeder;
use App\ClienteMail;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\Api\ClienteEmailApi;

class ClienteEmailPolicyComoEmpleadoTest extends ApiTestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use ClienteEmailApi;

    protected $usuario;
    protected $cabeceras;
    protected $email;
    protected $clienteID;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoEmpleado();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->email = factory(ClienteMail::class)->create();
        $this->clienteID = $this->email->cliente_id;
    }

    /**
     * Ver emails
     */

    public function test_el_empleado_puede_ver_los_emails_del_cliente()
    {
        $this->usuario->givePermissionTo('ver clientes');

        $respuesta = $this->obtenerEmails($this->clienteID);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_los_emails_del_cliente()
    {
        $respuesta = $this->obtenerEmails($this->clienteID);
        $respuesta->assertStatus(403);
    }

    /**
     * Ver email
     */

    public function test_el_empleado_puede_ver_el_email_del_cliente()
    {
        $this->usuario->givePermissionTo('ver clientes');

        $respuesta = $this->obtenerEmail($this->clienteID, $this->email->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_ver_el_email_del_cliente()
    {
        $respuesta = $this->obtenerEmail($this->clienteID, $this->email->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear email
     */

    public function test_el_empleado_puede_crear_el_email_del_cliente()
    {
        $this->usuario->givePermissionTo('crear clientes');

        $respuesta = $this->crearEmail($this->clienteID);
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_crear_el_email_del_cliente()
    {
        $respuesta = $this->crearEmail($this->clienteID);
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar email
     */

    public function test_el_empleado_puede_actualizar_el_email_del_cliente()
    {
        $this->usuario->givePermissionTo('actualizar clientes');

        $respuesta = $this->actualizarEmail($this->clienteID, $this->email->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_actualizar_el_email_del_cliente()
    {
        $respuesta = $this->actualizarEmail($this->clienteID, $this->email->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar email
     */

    public function test_el_empleado_puede_eliminar_el_email_del_cliente()
    {
        $this->usuario->givePermissionTo('eliminar clientes');

        $respuesta = $this->eliminarEmail($this->clienteID, $this->email->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_eliminar_el_email_del_cliente()
    {
        $respuesta = $this->eliminarEmail($this->clienteID, $this->email->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar email
     */

    public function test_el_empleado_puede_restaurar_el_email_del_cliente()
    {
        $this->usuario->givePermissionTo('eliminar clientes');

        $respuesta = $this->restaurarEmail($this->clienteID, $this->email->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_puede_rno_estaurar_el_email_del_cliente()
    {
        $respuesta = $this->restaurarEmail($this->clienteID, $this->email->id);
        $respuesta->assertStatus(403);
    }
}
