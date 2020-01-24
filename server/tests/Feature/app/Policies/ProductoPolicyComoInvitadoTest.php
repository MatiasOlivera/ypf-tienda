<?php

namespace Tests\Feature\app\Policies;

use App\Producto;
use Tests\ApiTestCase;
use AutorizacionSeeder;
use CategoriaProductoSeeder;
use Tests\Feature\Utilidades\Api\ProductoApi;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductoPolicyComoInvitadoTest extends ApiTestCase
{
    use ProductoApi;
    use RefreshDatabase;
    use EloquenceSolucion;

    protected $cabeceras;

    protected function setUp()
    {
        parent::setUp();

        $this->cabeceras = [];
    }

    /**
     * Ver productos
     */

    public function test_el_invitado_puede_ver_los_productos()
    {
        $respuesta = $this->obtenerProductos();
        $respuesta->assertOk();
    }

    /**
     * Ver producto
     */

    public function test_el_invitado_puede_ver_el_producto()
    {
        $this->seed(CategoriaProductoSeeder::class);
        $producto = factory(Producto::class)->create();

        $respuesta = $this->obtenerProducto($producto->id);
        $respuesta->assertOk();
    }
}
