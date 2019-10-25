<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Producto;
use Tests\TestCase;
use CategoriaProductoSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EstructuraProducto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class ProductosFavoritosControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EstructuraProducto;
    use EstructuraJsonHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategoriaProductoSeeder::class);
    }

    private function crearProducto()
    {
        return factory(Producto::class, 1)->create()->toArray()[0];
    }

    /**
     * Debería guardar el producto como favorito
     */
    public function testDeberiaGuardarProductoComoFavorito()
    {
        $producto = $this->crearProducto();
        $id = $producto['id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/productos/$id/favorito", $producto);

        $estructura = $this->getEstructuraProducto();
        $producto['es_favorito'] = true;

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $producto,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ASOCIADOS',
                    'descripcion' => "Se guardo el producto {$producto['nombre']} como favorito"
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
        $producto = $this->crearProducto();
        $id = $producto['id'];

        $cabeceras = $this->loguearseComo('defecto');

        $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/productos/$id/favorito", $producto);

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/productos/$id/favorito");

        $estructura = $this->getEstructuraProducto();
        $producto['es_favorito'] = false;

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $producto,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'DESASOCIADOS',
                    'descripcion' => "Se quito el producto {$producto['nombre']} de la lista de favoritos"
                ]
            ]);

        $esFavorito = $respuesta->getData(true)['producto']['es_favorito'];
        $this->assertNotTrue($esFavorito);
    }
}
