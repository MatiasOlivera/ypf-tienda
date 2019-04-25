<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use UsuariosSeeder;
use App\Auxiliares\Consulta;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConsultaTest extends TestCase
{
    use RefreshDatabase;

    public $parametrosPorDefecto;

    public function setUp(): void
    {
        parent::setUp();

        $this->parametrosPorDefecto = [
            'modelo' => 'User',
            'campos' => null,
            'relaciones' => null,
            'eliminados' => null,
            'buscar' => null,
            'paginado' => null
        ];
    }

    public function consultar(array $parametros)
    {
        $consulta = new Consulta();
        $consulta->setParametros($parametros);
        return $consulta->ejecutarConsulta();
    }

    /**
     * Debería obtener una respuesta con datos y con paginación
     *
     * @return void
     */
    public function comprobarRespuesta($respuesta)
    {
        $this->assertIsArray($respuesta);
        $this->assertArrayHasKey('datos', $respuesta);
        $this->assertArrayHasKey('paginacion', $respuesta);

        $this->assertIsArray($respuesta['datos']);
        $this->assertIsArray($respuesta['paginacion']);

        $claves = [
            'total',
            'per_page',
            'current_page',
            'last_page',
            'first_page_url',
            'last_page_url',
            'next_page_url',
            'prev_page_url',
            'path',
            'from',
            'to'
        ];

        foreach ($claves as $clave) {
            $this->assertArrayHasKey($clave, $respuesta['paginacion']);
        }
    }

    /**
     * Debería obtener un listado vacío
     *
     * @return void
     */
    public function testDeberiaObtenerUnListadoVacio()
    {
        $respuesta = $this->consultar($this->parametrosPorDefecto);

        $this->comprobarRespuesta($respuesta);
        $this->assertEquals([], $respuesta['datos']);
    }

    /**
     * Debería obtener un listado de instancias
     *
     * @return void
     */
    public function testDeberiaObtenerUnListado()
    {
        $seeder = new UsuariosSeeder();
        $seeder->run();

        $respuesta = $this->consultar($this->parametrosPorDefecto);
        $this->comprobarRespuesta($respuesta);

        $usuarios = User::paginate(10)->items();
        $paginacion =  [
            "total" => 20,
            "per_page" => 10,
            "current_page" => 1,
            "last_page" => 2,
            "first_page_url" => "http://localhost?page=1",
            "last_page_url" => "http://localhost?page=2",
            "next_page_url" => "http://localhost?page=2",
            "prev_page_url" => null,
            "path" => "http://localhost",
            "from" => 1,
            "to" => 10
        ];

        $this->assertEquals($usuarios, $respuesta['datos']);
        $this->assertEquals($paginacion, $respuesta['paginacion']);
    }

    /**
     * Debería obtener un listado de instancias con campos específicos
     *
     * @return void
     */
    public function testDeberiaObtenerUnListadoConCamposEspecificos()
    {
        $seeder = new UsuariosSeeder();
        $seeder->run();

        $parametros = array_replace(
            $this->parametrosPorDefecto,
            ['campos' => ['id', 'name', 'email']]
        );
        $respuesta = $this->consultar($parametros);
        $this->comprobarRespuesta($respuesta);

        $usuarios = User::select(['id', 'name', 'email'])->paginate(10);
        $paginacion =  [
            "total" => 20,
            "per_page" => 10,
            "current_page" => 1,
            "last_page" => 2,
            "first_page_url" => "http://localhost?page=1",
            "last_page_url" => "http://localhost?page=2",
            "next_page_url" => "http://localhost?page=2",
            "prev_page_url" => null,
            "path" => "http://localhost",
            "from" => 1,
            "to" => 10
        ];

        $this->assertEquals($usuarios->items(), $respuesta['datos']);
        $this->assertEquals($paginacion, $respuesta['paginacion']);
    }

    /**
     * Debería obtener un listado de instancias con las relaciones especificas
     *
     * @return void
     */
    public function testDeberiaObtenerUnListadoConRelaciones()
    {
        $seeder = new UsuariosSeeder();
        $seeder->run();

        $parametros = array_replace(
            $this->parametrosPorDefecto,
            ['relaciones' => ['cliente']]
        );
        $respuesta = $this->consultar($parametros);
        $this->comprobarRespuesta($respuesta);

        $usuarios = User::with('cliente')->paginate(10);
        $paginacion =  [
            "total" => 20,
            "per_page" => 10,
            "current_page" => 1,
            "last_page" => 2,
            "first_page_url" => "http://localhost?page=1",
            "last_page_url" => "http://localhost?page=2",
            "next_page_url" => "http://localhost?page=2",
            "prev_page_url" => null,
            "path" => "http://localhost",
            "from" => 1,
            "to" => 10
        ];

        $this->assertEquals($usuarios->items(), $respuesta['datos']);
        $this->assertEquals($paginacion, $respuesta['paginacion']);
    }

     /**
     * Debería obtener un listado de instancias eliminadas
     *
     * @return void
     */
    public function testDeberiaObtenerUnListadoConInstanciasEliminadas()
    {
        $seeder = new UsuariosSeeder();
        $seeder->run();

        $usuarios = User::paginate(10);

        foreach ($usuarios as $usuario) {
            $usuario->delete();
        }

        $usuariosEliminados = User::onlyTrashed()->paginate(10)->items();

        $parametros = array_replace($this->parametrosPorDefecto, ['eliminados' => true]);
        $respuesta = $this->consultar($parametros);
        $this->comprobarRespuesta($respuesta);


        $paginacion =  [
            "total" => 10,
            "per_page" => 10,
            "current_page" => 1,
            "last_page" => 1,
            "first_page_url" => "http://localhost?page=1",
            "last_page_url" => "http://localhost?page=1",
            "next_page_url" => null,
            "prev_page_url" => null,
            "path" => "http://localhost",
            "from" => 1,
            "to" => 10
        ];

        $this->assertEquals($usuariosEliminados, $respuesta['datos']);
        $this->assertEquals($paginacion, $respuesta['paginacion']);
    }

    /**
     * Debería poder realizar busquedas dentro del listado de instancias
     *
     * @return void
     */
    public function testDeberiaPoderRealizarBusquedas()
    {
        $seeder = new UsuariosSeeder();
        $seeder->run();

        $parametros = array_replace(
            $this->parametrosPorDefecto,
            ['buscar' => 'Valen','campos' => ['id', 'name']]
        );
        $respuesta = $this->consultar($parametros);

        $usuarios = User::select(['id', 'name'])
                    ->where('name', 'like', '%Valen%')
                    ->paginate(10)
                    ->items();

        $paginacion =  [
            "total" => 2,
            "per_page" => 10,
            "current_page" => 1,
            "last_page" => 1,
            "first_page_url" => "http://localhost?page=1",
            "last_page_url" => "http://localhost?page=1",
            "next_page_url" => null,
            "prev_page_url" => null,
            "path" => "http://localhost",
            "from" => 1,
            "to" => 2
        ];

        $this->assertEquals($usuarios, $respuesta['datos']);
        $this->assertEquals($paginacion, $respuesta['paginacion']);
    }

    /**
    * Debería poder paginar el listado de instancias
    *
    * @return void
    */
    public function testDeberiaPoderPaginar()
    {
        $seeder = new UsuariosSeeder();
        $seeder->run();

        $parametros = array_replace(
            $this->parametrosPorDefecto,
            ['paginado' => ['porPagina' => 5]]
        );
        $respuesta = $this->consultar($parametros);

        $usuarios = User::paginate(5)->items();
        $paginacion =  [
            "total" => 20,
            "per_page" => 5,
            "current_page" => 1,
            "last_page" => 4,
            "first_page_url" => "http://localhost?page=1",
            "last_page_url" => "http://localhost?page=4",
            "next_page_url" => "http://localhost?page=2",
            "prev_page_url" => null,
            "path" => "http://localhost",
            "from" => 1,
            "to" => 5
        ];

        $this->assertEquals($usuarios, $respuesta['datos']);
        $this->assertEquals($paginacion, $respuesta['paginacion']);
    }

    /**
    * Debería poder ordenar el listado de instancias
    *
    * @return void
    */
    public function testDeberiaPoderOrdenar()
    {
        $seeder = new UsuariosSeeder();
        $seeder->run();

        $parametros = array_replace(
            $this->parametrosPorDefecto,
            ['paginado' => ['ordenadoPor' => 'name', 'orden' => true]]
        );
        $respuesta = $this->consultar($parametros);

        $usuarios = User::orderBy('name', 'ASC')->paginate(10)->items();
        $paginacion =  [
            "total" => 20,
            "per_page" => 10,
            "current_page" => 1,
            "last_page" => 2,
            "first_page_url" => "http://localhost?page=1",
            "last_page_url" => "http://localhost?page=2",
            "next_page_url" => "http://localhost?page=2",
            "prev_page_url" => null,
            "path" => "http://localhost",
            "from" => 1,
            "to" => 10
        ];

        $this->assertEquals($usuarios, $respuesta['datos']);
        $this->assertEquals($paginacion, $respuesta['paginacion']);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->parametros);
    }
}