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

class ClienteRazonSocialPolicyComoClienteTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use ClienteRazonSocialApi;

    protected $usuario;
    protected $cabeceras;
    protected $clienteID;
    protected $razonSocialAsociada;
    protected $clienteNoAsociado;
    protected $razonSocialNoAsociada;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $this->clienteNoAsociado = factory(Cliente::class)->create();
        $this->razonSocialNoAsociada = factory(ClienteRazonSocial::class)->create();
        $this->clienteNoAsociado->razonesSociales()->attach($this->razonSocialNoAsociada->id);

        $login = $this->loguearseComoCliente();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];
        $this->clienteID = $this->usuario->id_cliente;

        $this->razonSocialAsociada = factory(ClienteRazonSocial::class)->create();
        $this->asociarClienteRazonSocial(
            $this->cabeceras,
            $this->clienteID,
            $this->razonSocialAsociada->id
        );
    }

    /**
     * Ver razones sociales
     */

    public function test_el_cliente_usuario_puede_ver_las_razones_sociales_del_cliente_asociado()
    {
        $respuesta = $this->obtenerRazonesSociales($this->clienteID);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_ver_las_razones_sociales_de_un_cliente_no_asociado()
    {
        $respuesta = $this->obtenerRazonesSociales($this->clienteNoAsociado->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Ver razón social
     */

    public function test_el_cliente_usuario_puede_ver_la_razon_social_del_cliente_asociado()
    {
        $respuesta = $this->obtenerRazonSocial($this->clienteID, $this->razonSocialAsociada->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_ver_la_razon_social_de_un_cliente_no_asociado()
    {
        $respuesta = $this->obtenerRazonSocial($this->clienteNoAsociado->id, $this->razonSocialNoAsociada->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear razón social
     */

    public function test_el_cliente_usuario_puede_crear_una_razon_social_del_cliente_asociado()
    {
        $respuesta = $this->crearRazonSocial($this->clienteID);
        $respuesta->assertStatus(422);
    }

    public function test_el_cliente_usuario_no_puede_crear_una_razon_social_a_un_cliente_no_asociado()
    {
        $cliente = factory(Cliente::class)->create();
        $respuesta = $this->crearRazonSocial($cliente->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar razón social
     */

    public function test_el_cliente_usuario_puede_actualizar_una_razon_social_del_cliente_asociado()
    {
        $respuesta = $this->actualizarRazonSocial($this->clienteID, $this->razonSocialAsociada->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_cliente_usuario_no_puede_actualizar_una_razon_social_de_un_cliente_no_asociado()
    {
        $clienteID = $this->clienteNoAsociado->id;
        $razonID = $this->razonSocialNoAsociada->id;
        $respuesta = $this->actualizarRazonSocial($clienteID, $razonID);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar razón social
     */

    public function test_el_cliente_usuario_puede_eliminar_una_razon_social_del_cliente_asociado()
    {
        $respuesta = $this->eliminarRazonSocial($this->clienteID, $this->razonSocialAsociada->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_eliminar_una_razon_social_de_un_cliente_no_asociado()
    {
        $clienteID = $this->clienteNoAsociado->id;
        $razonID = $this->razonSocialNoAsociada->id;
        $respuesta = $this->eliminarRazonSocial($clienteID, $razonID);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar razón social
     */

    public function test_el_cliente_usuario_puede_restaurar_una_razon_social_del_cliente_asociado()
    {
        $respuesta = $this->restaurarRazonSocial($this->clienteID, $this->razonSocialAsociada->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_restaurar_una_razon_social_de_un_cliente_no_asociado()
    {
        $clienteID = $this->clienteNoAsociado->id;
        $razonID = $this->razonSocialNoAsociada->id;
        $respuesta = $this->restaurarRazonSocial($clienteID, $razonID);
        $respuesta->assertStatus(403);
    }

    /**
     * Asociar el cliente con la razón
     */

    public function test_el_cliente_usuario_puede_asociar_el_cliente_y_la_razon()
    {
        $respuesta = $this->asociarClienteRazonSocial(
            $this->cabeceras,
            $this->clienteID,
            $this->razonSocialNoAsociada->id
        );
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_asociar_el_cliente_y_la_razon()
    {
        $respuesta = $this->asociarClienteRazonSocial(
            $this->cabeceras,
            $this->clienteNoAsociado->id,
            $this->razonSocialNoAsociada->id
        );
        $respuesta->assertStatus(403);
    }

    /**
     * Desasociar el cliente y la razón
     */
    public function test_el_cliente_usuario_puede_desasociar_el_cliente_y_la_razon()
    {
        $respuesta = $this->desasociarClienteRazonSocial(
            $this->cabeceras,
            $this->clienteID,
            $this->razonSocialAsociada->id
        );
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_desasociar_el_cliente_y_la_razon()
    {
        $respuesta = $this->desasociarClienteRazonSocial(
            $this->cabeceras,
            $this->clienteNoAsociado->id,
            $this->razonSocialNoAsociada->id
        );
        $respuesta->assertStatus(403);
    }
}
