<?php

namespace Tests\Feature\app\Policies;

use App\Cliente;
use Tests\TestCase;
use AutorizacionSeeder;
use App\ClienteMail;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\Api\ClienteEmailApi;

class ClienteEmailPolicyComoClienteTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use ClienteEmailApi;

    protected $usuario;
    protected $cabeceras;
    protected $clienteID;
    protected $emailAsociado;
    protected $emailNoAsociado;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoCliente();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];
        $this->clienteID = $this->usuario->id_cliente;

        $this->emailAsociado = factory(ClienteMail::class)->create([
            'cliente_id' => $this->clienteID
        ]);
        $this->emailNoAsociado = factory(ClienteMail::class)->create();
    }

    /**
     * Ver emails
     */

    public function test_el_cliente_usuario_puede_ver_los_emails_del_cliente_asociado()
    {
        $respuesta = $this->obtenerEmails($this->clienteID);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_ver_los_emails_de_un_cliente_no_asociado()
    {
        $cliente = factory(Cliente::class)->create();
        $respuesta = $this->obtenerEmails($cliente->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Ver email
     */

    public function test_el_cliente_usuario_puede_ver_el_email_del_cliente_asociado()
    {
        $respuesta = $this->obtenerEmail($this->clienteID, $this->emailAsociado->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_ver_el_email_de_un_cliente_no_asociado()
    {
        $clienteID = $this->emailNoAsociado->cliente_id;
        $emailID = $this->emailNoAsociado->id;
        $respuesta = $this->obtenerEmail($clienteID, $emailID);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear email
     */

    public function test_el_cliente_usuario_puede_crear_un_email_del_cliente_asociado()
    {
        $respuesta = $this->crearEmail($this->clienteID);
        $respuesta->assertStatus(422);
    }

    public function test_el_cliente_usuario_no_puede_crear_un_email_a_un_cliente_no_asociado()
    {
        $cliente = factory(Cliente::class)->create();
        $respuesta = $this->crearEmail($cliente->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar email
     */

    public function test_el_cliente_usuario_puede_actualizar_un_email_del_cliente_asociado()
    {
        $respuesta = $this->actualizarEmail($this->clienteID, $this->emailAsociado->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_cliente_usuario_no_puede_actualizar_un_email_de_un_cliente_no_asociado()
    {
        $clienteID = $this->emailNoAsociado->cliente_id;
        $emailID = $this->emailNoAsociado->id;
        $respuesta = $this->actualizarEmail($clienteID, $emailID);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar email
     */

    public function test_el_cliente_usuario_puede_eliminar_un_email_del_cliente_asociado()
    {
        $respuesta = $this->eliminarEmail($this->clienteID, $this->emailAsociado->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_eliminar_un_email_de_un_cliente_no_asociado()
    {
        $clienteID = $this->emailNoAsociado->cliente_id;
        $emailID = $this->emailNoAsociado->id;
        $respuesta = $this->eliminarEmail($clienteID, $emailID);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar email
     */

    public function test_el_cliente_usuario_puede_restaurar_un_email_del_cliente_asociado()
    {
        $respuesta = $this->restaurarEmail($this->clienteID, $this->emailAsociado->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_restaurar_un_email_de_un_cliente_no_asociado()
    {
        $clienteID = $this->emailNoAsociado->cliente_id;
        $emailID = $this->emailNoAsociado->id;
        $respuesta = $this->restaurarEmail($clienteID, $emailID);
        $respuesta->assertStatus(403);
    }
}
