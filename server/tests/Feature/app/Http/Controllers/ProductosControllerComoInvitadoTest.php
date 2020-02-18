<?php

namespace Tests\Feature\Http\Controllers;

use App\Producto;
use Tests\ApiTestCase;
use App\CategoriaProducto;
use CategoriaProductoSeeder;
use App\Http\Resources\ProductoResource;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Tests\Feature\Utilidades\EstructuraProducto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;
use Tests\Feature\Utilidades\Api\ProductoApiPublica;

class ProductosControllerComoInvitadoTest extends ApiTestCase
{
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraProducto;
    use ProductoApiPublica;
    use EstructuraJsonHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CategoriaProductoSeeder::class);
    }

    private function getEstructuraProductos(): array
    {
        $paginacion = $this->estructuraPaginacion;
        unset($paginacion['paginacion']['rutas']);
        return array_merge(['productos'], $paginacion);
    }

    public function test_el_invitado_deberia_obtener_productos()
    {
        factory(Producto::class, 10)->create();

        $respuesta = $this->obtenerProductos();

        $estructura = $this->getEstructuraProductos();
        $productos = Producto::orderBy('nombre', 'ASC')->get();
        $coleccionProductos = ProductoResource::collection($productos)->resolve();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['productos' => $coleccionProductos]);
    }

    public function test_el_invitado_deberia_obtener_un_producto()
    {
        $producto = factory(Producto::class)->create();

        $respuesta = $this->obtenerProducto($producto->id);

        $estructura = $this->getEstructuraProductoComoInvitado();
        $recursoProducto = ProductoResource::make($producto)->resolve();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertExactJson(['producto' => $recursoProducto]);
    }
}
