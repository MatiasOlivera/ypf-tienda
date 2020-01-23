<?php

namespace Tests\Feature\Http\Controllers;

use Tests\ApiTestCase;
use AutorizacionSeeder;
use App\CategoriaProducto;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class CategoriaProductoControllerTest extends ApiTestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraJsonHelper;

    protected $usuario;
    protected $cabeceras;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoSuperAdministrador();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];
    }

    private $estructuraCategoria = [
        'categoria' => [
            'id',
            'descripcion',
            'created_at',
            'updated_at',
            'deleted_at'
        ]
    ];

    private function getEstructuraCategorias(): array
    {
        return array_merge(['categorias'], $this->estructuraPaginacion);
    }

    private function getEstructuraCategoria(): array
    {
        return array_merge($this->estructuraCategoria, $this->estructuraMensaje);
    }

    private function crearCategoria($cabeceras, $categoria = null)
    {
        if ($categoria === null) {
            $categoria = ['descripcion' => 'Automotriz Alta Gama'];
        }

        $respuestaCategoria = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/categorias-productos', $categoria);

        return $respuestaCategoria->getData(true)['categoria'];
    }

    /**
     * No debería obtener ninguna categoria
     *
     * @return void
     */
    public function testNoDeberiaObtenerNingunaCategoria()
    {
        $respuesta = $this->json('GET', 'api/categorias-productos');

        $estructura = $this->getEstructuraCategorias();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
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

        $respuesta = $this->json('GET', 'api/categorias-productos');

        $estructura = $this->getEstructuraCategorias();
        $categorias = CategoriaProducto::orderBy('descripcion', 'ASC')->get()->toArray();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['categorias' => $categorias]);
    }

    /**
     * Debería crear una categoria
     */
    public function testDeberiaCrearUnaCategoria()
    {
        $categoria = ['descripcion' => 'Combustibles'];

        $respuesta = $this
            ->withHeaders($this->cabeceras)
            ->json('POST', 'api/categorias-productos', $categoria);

        $estructura = $this->getEstructuraCategoria();

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'categoria' => $categoria,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => 'La categoria Combustibles ha sido creada'
                ]
            ]);
    }

    /**
     * Debería obtener una categoría
     */
    public function testDeberiaObtenerUnaCategoria()
    {
        $categoriaGuardada = $this->crearCategoria($this->cabeceras);
        $id = $categoriaGuardada['id'];

        $respuesta = $this->json('GET', "api/categorias-productos/$id");

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($this->estructuraCategoria)
            ->assertJson([
                'categoria' => $categoriaGuardada
            ]);
    }

    /**
     * Debería editar una categoria
     */
    public function testDeberiaEditarUnaCategoria()
    {
        $categoria = ['descripcion' => 'Combustible'];
        $categoriaGuardada = $this->crearCategoria($this->cabeceras, $categoria);
        $id = $categoriaGuardada['id'];

        $categoriaModificada = ['descripcion' => 'Combustibles'];
        $respuesta = $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/categorias-productos/$id", $categoriaModificada);

        $categoriaEsperada = array_merge($categoriaGuardada, $categoriaModificada);
        unset($categoriaEsperada['updated_at']);
        $estructura = $this->getEstructuraCategoria();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'categoria' => $categoriaEsperada,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'La categoria Combustible ha sido modificada'
                ]
            ]);
    }

    /**
     * Debería eliminar una categoria
     */
    public function testDeberiaEliminarUnaCategoria()
    {
        $categoria = ['descripcion' => 'Combustibles'];
        $categoriaGuardada = $this->crearCategoria($this->cabeceras, $categoria);
        $id = $categoriaGuardada['id'];

        $respuesta = $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/categorias-productos/$id");

        $categoriaDB = CategoriaProducto::withTrashed()
            ->where('ID_CAT_prod', $id)
            ->firstOrFail()
            ->toArray();

        $estructura = $this->getEstructuraCategoria();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertExactJson([
                'categoria' => $categoriaDB,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ELIMINADO',
                    'descripcion' => 'La categoria Combustibles ha sido eliminada'
                ]
            ]);
    }

    /**
     * Debería restaurar una categoria
     */
    public function testDeberiaRestaurarUnaCategoria()
    {
        $categoria = ['descripcion' => 'Combustibles'];
        $categoriaGuardada = $this->crearCategoria($this->cabeceras, $categoria);
        $id = $categoriaGuardada['id'];

        $this->withHeaders($this->cabeceras)
            ->json('DELETE', "api/categorias-productos/$id");

        $respuesta = $this->withHeaders($this->cabeceras)
            ->json('POST', "api/categorias-productos/$id/restaurar");

        $categoriaDB = CategoriaProducto::withTrashed()
            ->where('ID_CAT_prod', $id)
            ->firstOrFail()
            ->toArray();

        $estructura = $this->getEstructuraCategoria();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertExactJson([
                'categoria' => $categoriaDB,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'RESTAURADO',
                    'descripcion' => 'La categoria Combustibles ha sido dada de alta'
                ]
            ]);
    }
}
