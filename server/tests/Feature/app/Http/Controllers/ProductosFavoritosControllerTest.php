<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Producto;
use Tests\ApiTestCase;
use CategoriaProductoSeeder;
use App\Http\Resources\ProductoResource;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EstructuraProducto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;
use Tests\Feature\Utilidades\Api\ProductoFavoritoApi;

class ProductosFavoritosControllerTest extends ApiTestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EstructuraProducto;
    use ProductoFavoritoApi;
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

    /**
     * Debería guardar el producto como favorito
     */
    public function testDeberiaGuardarProductoComoFavorito()
    {
        $producto = factory(Producto::class)->create();

        $respuesta = $this->agregarFavorito($producto->id);

        $estructura = $this->getEstructuraProductoComoCliente();
        $recursoProducto = ProductoResource::make($producto)->resolve();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $recursoProducto,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ASOCIADOS',
                    'descripcion' => "Se guardo el producto {$producto->nombre} como favorito"
                ]
            ]);

        $esFavorito = $respuesta->getData(true)['producto']['es_favorito'];
        $this->assertTrue($esFavorito);
    }

    /**
     * Debería quitar el producto de la lista de favoritos
     */
    public function testDeberiaQuitarProductoDeFavoritos()
    {
        $producto = factory(Producto::class)->create();

        $this->agregarFavorito($producto->id);

        $respuesta = $this->eliminarFavorito($producto->id);

        $estructura = $this->getEstructuraProductoComoCliente();
        $recursoProducto = ProductoResource::make($producto)->resolve();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $recursoProducto,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'DESASOCIADOS',
                    'descripcion' => "Se quito el producto {$producto->nombre} de la lista de favoritos"
                ]
            ]);

        $esFavorito = $respuesta->getData(true)['producto']['es_favorito'];
        $this->assertNotTrue($esFavorito);
    }
}
