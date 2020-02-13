<?php

namespace Tests\Feature\app\Policies;

use App\Cotizacion;
use Tests\ApiTestCase;
use CategoriaProductoSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\Api\CotizacionApi;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CotizacionPolicyComoClienteTest extends ApiTestCase
{
    use AuthHelper;
    use CotizacionApi;
    use RefreshDatabase;
    use EloquenceSolucion;

    protected $usuario;
    protected $cabeceras;
    protected $clienteID;
    protected $cotizacionAsociada;
    protected $cotizacionNoAsociada;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(CategoriaProductoSeeder::class);

        $login = $this->loguearseComoCliente();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];
        $this->clienteID = $this->usuario->id_cliente;

        $this->cotizacionAsociada = factory(Cotizacion::class)->create([
            'cliente_id' => $this->clienteID
        ]);
        $this->cotizacionNoAsociada = factory(Cotizacion::class)->create();
    }

    /**
     * Ver cotizaciones
     */

    public function test_el_cliente_usuario_no_puede_ver_todas_las_cotizaciones()
    {
        $respuesta = $this->obtenerCotizaciones();
        $respuesta->assertStatus(403);
    }

    public function test_el_cliente_usuario_puede_ver_las_cotizaciones_propias()
    {
        $respuesta = $this->obtenerCotizacionesDelCliente($this->clienteID);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_ver_las_cotizaciones_de_otro_cliente()
    {
        $respuesta = $this->obtenerCotizacionesDelCliente($this->cotizacionNoAsociada->cliente->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Ver cotizacion
     */

    public function test_el_cliente_usuario_puede_ver_la_cotizacion()
    {
        $respuesta = $this->obtenerCotizacion($this->cotizacionAsociada->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_ver_la_cotizacion()
    {
        $respuesta = $this->obtenerCotizacion($this->cotizacionNoAsociada->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Crear cotizacion
     */

    public function test_el_cliente_usuario_puede_crear_una_cotizacion()
    {
        $respuesta = $this->crearCotizacionDelCliente($this->clienteID);
        $respuesta->assertStatus(422);
    }

    public function test_el_cliente_usuario_no_puede_crear_una_cotizacion()
    {
        $respuesta = $this->crearCotizacionDelCliente($this->cotizacionNoAsociada->cliente->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar cotizacion
     */

    public function test_el_cliente_usuario_puede_actualizar_una_cotizacion()
    {
        $respuesta = $this->actualizarCotizacion($this->cotizacionAsociada->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_cliente_usuario_no_puede_actualizar_una_cotizacion()
    {
        $respuesta = $this->actualizarCotizacion($this->cotizacionNoAsociada->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar cotizacion
     */

    public function test_el_cliente_usuario_puede_eliminar_una_cotizacion()
    {
        $respuesta = $this->eliminarCotizacion($this->cotizacionAsociada->id);
        $respuesta->assertOk();
    }

    public function test_el_cliente_usuario_no_puede_eliminar_una_cotizacion()
    {
        $respuesta = $this->eliminarCotizacion($this->cotizacionNoAsociada->id);
        $respuesta->assertStatus(403);
    }
}
