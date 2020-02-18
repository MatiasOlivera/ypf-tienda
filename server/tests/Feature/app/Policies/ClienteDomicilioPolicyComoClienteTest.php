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

class ClienteDomicilioPolicyComoClienteTest extends ApiTestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use ClienteDomicilioApi;

    protected $usuario;
    protected $cabeceras;
    protected $clienteID;
    protected $domicilioAsociado;
    protected $domicilioNoAsociado;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoCliente();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];
        $this->clienteID = $this->usuario->id_cliente;

        $this->domicilioAsociado = factory(ClienteDomicilio::class)->create([
            'cliente_id' => $this->clienteID
        ]);
        $this->domicilioNoAsociado = factory(ClienteDomicilio::class)->create();
    }

    /**
     * Ver domicilios
     */

    public function test_el_cliente_usuario_puede_ver_los_domicilios_del_cliente_asociado()
    {
        $respuesta = $this->obtenerDomicilios($this->clienteID);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_ver_los_domicilios_de_un_cliente_no_asociado()
    {
        $cliente = factory(Cliente::class)->create();
        $respuesta = $this->obtenerDomicilios($cliente->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Ver domicilio
     */

    public function test_el_cliente_usuario_puede_ver_el_domicilio_del_cliente_asociado()
    {
        $respuesta = $this->obtenerDomicilio($this->clienteID, $this->domicilioAsociado->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_ver_el_domicilio_de_un_cliente_no_asociado()
    {
        $clienteID = $this->domicilioNoAsociado->cliente_id;
        $domicilioID = $this->domicilioNoAsociado->id;
        $respuesta = $this->obtenerDomicilio($clienteID, $domicilioID);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear domicilio
     */

    public function test_el_cliente_usuario_puede_crear_un_domicilio_del_cliente_asociado()
    {
        $respuesta = $this->crearDomicilio($this->clienteID);
        $respuesta->assertStatus(422);
    }

    public function test_el_cliente_usuario_no_puede_crear_un_domicilio_a_un_cliente_no_asociado()
    {
        $cliente = factory(Cliente::class)->create();
        $respuesta = $this->crearDomicilio($cliente->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar domicilio
     */

    public function test_el_cliente_usuario_puede_actualizar_un_domicilio_del_cliente_asociado()
    {
        $respuesta = $this->actualizarDomicilio($this->clienteID, $this->domicilioAsociado->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_cliente_usuario_no_puede_actualizar_un_domicilio_de_un_cliente_no_asociado()
    {
        $clienteID = $this->domicilioNoAsociado->cliente_id;
        $domicilioID = $this->domicilioNoAsociado->id;
        $respuesta = $this->actualizarDomicilio($clienteID, $domicilioID);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar domicilio
     */

    public function test_el_cliente_usuario_puede_eliminar_un_domicilio_del_cliente_asociado()
    {
        $respuesta = $this->eliminarDomicilio($this->clienteID, $this->domicilioAsociado->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_eliminar_un_domicilio_de_un_cliente_no_asociado()
    {
        $clienteID = $this->domicilioNoAsociado->cliente_id;
        $domicilioID = $this->domicilioNoAsociado->id;
        $respuesta = $this->eliminarDomicilio($clienteID, $domicilioID);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar domicilio
     */

    public function test_el_cliente_usuario_puede_restaurar_un_domicilio_del_cliente_asociado()
    {
        $respuesta = $this->restaurarDomicilio($this->clienteID, $this->domicilioAsociado->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_restaurar_un_domicilio_de_un_cliente_no_asociado()
    {
        $clienteID = $this->domicilioNoAsociado->cliente_id;
        $domicilioID = $this->domicilioNoAsociado->id;
        $respuesta = $this->restaurarDomicilio($clienteID, $domicilioID);
        $respuesta->assertStatus(403);
    }
}
