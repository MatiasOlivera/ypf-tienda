<?php

namespace Tests\Feature\app\Policies;

use Tests\ApiTestCase;
use AutorizacionSeeder;
use App\CategoriaProducto;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\Api\CategoriaProductoApi;

class CategoriaProductoPolicyComoInvitadoTest extends ApiTestCase
{
    use RefreshDatabase;
    use EloquenceSolucion;
    use CategoriaProductoApi;

    protected $cabeceras;

    protected function setUp()
    {
        parent::setUp();

        $this->cabeceras = [];
    }

    /**
     * Ver categorias
     */

    public function test_el_invitado_puede_ver_las_categorias_de_producto()
    {
        $respuesta = $this->obtenerCategorias();
        $respuesta->assertOk();
    }

    /**
     * Ver categoria
     */

    public function test_el_invitado_puede_ver_la_categoria()
    {
        $categoriaProducto = factory(CategoriaProducto::class)->create();
        $respuesta = $this->obtenerCategoria($categoriaProducto->id);
        $respuesta->assertOk();
    }
}
