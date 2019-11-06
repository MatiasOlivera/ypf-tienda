<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Pedido;
use Tests\TestCase;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EstructuraPedido;
use App\Http\Resources\Pedido\PedidoCollection;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class PedidoControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraJsonHelper;
    use EstructuraPedido;

    private function getEstructuraPedidos(): array
    {
        $paginacion = $this->estructuraPaginacion;
        unset($paginacion['paginacion']['rutas']);
        return array_merge(['pedidos'], $paginacion);
    }

    public function test_no_deberia_obtener_ningun_pedido()
    {
        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/pedidos');

        $estructura = $this->getEstructuraPedidos();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['pedidos' => []]);
    }

    public function test_deberia_obtener_pedidos()
    {
        factory(Pedido::class, 10)->create();

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/pedidos');

        $estructura = $this->getEstructuraPedidos();

        $pedidosTabla = Pedido::orderBy('id', 'DESC')->paginate(10);
        $pedidosColeccion = new PedidoCollection($pedidosTabla);
        $pedidosRespuesta = $pedidosColeccion->response()->getData(true);

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson($pedidosRespuesta);
    }
}
