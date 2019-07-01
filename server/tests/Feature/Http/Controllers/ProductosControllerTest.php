<?php

namespace Tests\Feature\Http\Controllers;

use App\Producto;
use Tests\TestCase;
use App\CategoriaProducto;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class ProductosControllerTest extends TestCase
{
    use AuthHelper;
    use EstructuraJsonHelper;
    use RefreshDatabase;

    private $estructuraProducto = [
        'producto' => [
            'id',
            'codigo',
            'nombre',
            'presentacion',
            'precio_por_mayor',
            'consumidor_final',
            'id_categoria',
            'created_at',
            'updated_at',
            'deleted_at'
        ]
    ];

    /**
     * No debería obtener ningun producto
     */
    public function testNoDeberiaObtenerNingunProducto()
    {
        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/productos');

        $estructura = array_merge(['productos'], $this->estructuraPaginacion);

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['productos' => []]);
    }

    /**
     * Debería obtener productos
     *
     * @return void
     */
    public function testDeberiaObtenerProductos()
    {
        factory(CategoriaProducto::class, 10)->create()->each(function ($categoria) {
            $categoria->productos()->save(factory(Producto::class)->make());
        });

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/productos');

        $estructura = array_merge(['productos'], $this->estructuraPaginacion);
        $productos = Producto::orderBy('nombre', 'ASC')->get()->toArray();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['productos' => $productos]);
    }

    /**
     * Debería crear un producto
     */
    public function testDeberiaCrearUnProducto()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $categoria = ['descripcion' => 'Automotriz Alta Gama'];

        $respuestaCategoria = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/categorias-productos', $categoria);

        $idCategoria = $respuestaCategoria->getData(true)['categoria']['id'];

        $producto = [
            'codigo' => '181696',
            'nombre' => 'ELAION F50 d1 0W-20 12/1',
            'presentacion' => 'Caja 12u / 1 litro',
            'precio_por_mayor' => 150,
            'consumidor_final' => 200,
            'id_categoria' => $idCategoria
        ];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/productos', $producto);

        $estructura = array_merge($this->estructuraProducto, $this->estructuraMensaje);

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $producto,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => 'El producto ELAION F50 d1 0W-20 12/1 ha sido creado'
                ]
            ]);
    }
}
