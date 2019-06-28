<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\CategoriaProducto;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriaProductoControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;

    /**
     * No debería obtener ninguna categoria
     *
     * @return void
     */
    public function testNoDeberiaObtenerNingunaCategoria()
    {
        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/categorias-productos');

        $respuesta
            ->assertOk()
            ->assertJsonStructure([
                'categorias',
                'paginacion' => [
                    'total',
                    'porPagina',
                    'paginaActual',
                    'ultimaPagina',
                    'desde',
                    'hasta',
                    'rutas' => [
                        'primeraPagina',
                        'ultimaPagina',
                        'siguientePagina',
                        'paginaAnterior',
                        'base'
                    ]
                ]
            ])
            ->assertJson(['categorias' => []]);
    }

    /**
     * Debería obtener categorias
     *
     * @return void
     */
    public function testDeberiaObtenerCategorias()
    {
        factory(CategoriaProducto::class, 10)->create();
        $categorias = CategoriaProducto::orderBy('descripcion', 'ASC')->get()->toArray();

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/categorias-productos');

        $respuesta
            ->assertOk()
            ->assertJsonStructure([
                'categorias',
                'paginacion' => [
                    'total',
                    'porPagina',
                    'paginaActual',
                    'ultimaPagina',
                    'desde',
                    'hasta',
                    'rutas' => [
                        'primeraPagina',
                        'ultimaPagina',
                        'siguientePagina',
                        'paginaAnterior',
                        'base'
                    ]
                ]
            ])
            ->assertJson(['categorias' => $categorias]);
    }

    /**
     * Debería crear una categoria
     */
    public function testDeberiaCrearUnaCategoria()
    {
        $categoria = ['descripcion' => 'Combustibles'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/categorias-productos', $categoria);

        /* TODO: seleccionar todas las columnas de la tabla */
        $categoriaDB = CategoriaProducto::select('id', 'descripcion', 'created_at', 'updated_at')
            ->get()
            ->toArray()[0];

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure([
                'categoria' => [
                    'id',
                    'descripcion',
                    'created_at',
                    'updated_at',
                    // 'deleted_at'
                ],
                'mensaje' => ['tipo', 'codigo', 'descripcion']
            ])
            ->assertExactJson([
                'categoria' => $categoriaDB,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => 'La categoria Combustibles ha sido creada'
                ]
            ]);
    }

    /**
     * Debería editar una categoria
     */
    public function testDeberiaEditarUnaCategoria()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $categoria = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/categorias-productos', ['descripcion' => 'Combustible']);

        $id = $categoria->getData(true)['categoria']['id'];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('PUT', "api/categorias-productos/$id", ['descripcion' => 'Combustibles']);

        $categoriaDB = CategoriaProducto::findOrFail($id)->toArray();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure([
                'categoria' => [
                    'id',
                    'descripcion',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ],
                'mensaje' => ['tipo', 'codigo', 'descripcion']
            ])
            ->assertExactJson([
                'categoria' => $categoriaDB,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'La categoria Combustible ha sido modificada'
                ]
            ]);
    }
}
