<?php

namespace Tests\Feature\Http\Controllers;

use App\Producto;
use Tests\ApiTestCase;
use App\CategoriaProducto;
use CategoriaProductoSeeder;
use App\Http\Resources\ProductoResource;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\Api\ProductoApi;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Tests\Feature\Utilidades\EstructuraProducto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class ProductosControllerComoClienteTest extends ApiTestCase
{
    use AuthHelper;
    use ProductoApi;
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraProducto;
    use EstructuraJsonHelper;

    protected $usuario;
    protected $cabeceras;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CategoriaProductoSeeder::class);

        $login = $this->loguearseComoCliente();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];
    }

    private function getEstructuraProductos(): array
    {
        $paginacion = $this->estructuraPaginacion;
        unset($paginacion['paginacion']['rutas']);
        return array_merge(['productos'], $paginacion);
    }

    public function test_el_cliente_usuario_no_deberia_obtener_ningun_producto()
    {
        $respuesta = $this->obtenerProductos();

        $estructura = $this->getEstructuraProductos();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['productos' => []]);
    }

    public function test_el_cliente_usuario_deberia_obtener_productos()
    {
        factory(CategoriaProducto::class, 10)
            ->create()
            ->each(function ($categoria) {
                $categoria->productos()->save(factory(Producto::class)->make());
            });

        $respuesta = $this->obtenerProductos();

        $estructura = $this->getEstructuraProductos();
        $productos = Producto::orderBy('nombre', 'ASC')->get();
        $coleccionProductos = ProductoResource::collection($productos)->resolve();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['productos' => $coleccionProductos]);
    }

    public function test_el_cliente_usuario_deberia_obtener_productos_favoritos()
    {
        factory(Producto::class, 10)->create();

        $producto = Producto::inRandomOrder()->first();

        $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/productos/{$producto->id}/favorito");

        $respuesta = $this
            ->withHeaders($this->cabeceras)
            ->json('GET', 'api/productos?soloFavoritos=true');

        $estructura = $this->getEstructuraProductos();
        $recursoProducto = ProductoResource::make($producto)->resolve();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['productos' => [$recursoProducto]]);
    }

    public function test_el_cliente_usuario_deberia_obtener_un_producto()
    {
        $producto = factory(Producto::class)->create();

        $respuesta = $this->obtenerProducto($producto->id);

        $estructura = $this->getEstructuraProductoComoCliente();
        $recursoProducto = ProductoResource::make($producto)->resolve();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertExactJson(['producto' => $recursoProducto]);
    }
}
