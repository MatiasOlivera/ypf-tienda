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
        return $consulta->ejecutarConsulta($parametros);
    }

    /**
     * Debería obtener un listado vacío
     *
     * @return void
     */
    public function testDeberiaObtenerUnListadoVacio()
    {
        $respuesta = $this->consultar($this->parametrosPorDefecto);
        $this->assertEquals([], $respuesta->items());
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

        $respuestaEsperada = User::paginate(10, ['*'], 'pagina')
            ->toArray();

        $this->assertEquals($respuestaEsperada, $respuesta->toArray());
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

        $respuestaEsperada = User::select(['id', 'name', 'email'])
            ->paginate(10, ['*'], 'pagina')
            ->toArray();

        $this->assertEquals($respuestaEsperada, $respuesta->toArray());
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

        $respuestaEsperada = User::with('cliente')
            ->paginate(10, ['*'], 'pagina')
            ->toArray();

        $this->assertEquals($respuestaEsperada, $respuesta->toArray());
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

        $respuestaEsperada = User::onlyTrashed()
            ->paginate(10, ['*'], 'pagina')
            ->toArray();

        $parametros = array_replace($this->parametrosPorDefecto, ['eliminados' => true]);
        $respuesta = $this->consultar($parametros);

        $this->assertEquals($respuestaEsperada, $respuesta->toArray());
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

        $respuestaEsperada = User::select(['id', 'name'])
                    ->where('name', 'like', '%Valen%')
                    ->paginate(10, ['*'], 'pagina')
                    ->toArray();

        $this->assertEquals($respuestaEsperada, $respuesta->toArray());
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

        $respuestaEsperada = User::paginate(5, ['*'], 'pagina')->toArray();

        $this->assertEquals($respuestaEsperada, $respuesta->toArray());
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
            ['paginado' => ['ordenarPor' => 'name', 'orden' => 'DESC']]
        );
        $respuesta = $this->consultar($parametros);

        $respuestaEsperada = User::orderBy('name', 'DESC')
            ->paginate(10, ['*'], 'pagina')
            ->toArray();

        $this->assertEquals($respuestaEsperada, $respuesta->toArray());
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->parametros);
    }
}
