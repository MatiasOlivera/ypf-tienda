<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Localidad;
use App\Provincia;
use Tests\TestCase;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class LocalidadControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EstructuraJsonHelper;

    private $estructuraLocalidad = [
        'localidad' => [
            'id',
            'nombre',
            'provincia_id',
            'created_at',
            'updated_at',
            'deleted_at',
            'provincia' => [
                'id',
                'nombre',
                'created_at',
                'updated_at',
                'deleted_at'
            ]
        ]
    ];

    private function getEstructuraLocalidades()
    {
        $paginacion = $this->estructuraPaginacion;
        unset($paginacion['paginacion']['rutas']);
        return array_merge(['localidades'], $paginacion);
    }

    private function getEstructuraLocalidad()
    {
        return array_merge($this->estructuraLocalidad, $this->estructuraMensaje);
    }

    private function crearProvincia($cabeceras)
    {
        $provincia = ['nombre' => 'Corrientes'];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/provincias', $provincia);

        return $respuesta->getData(true)['provincia'];
    }

    private function crearLocalidad($cabeceras, $localidad = null)
    {
        $provincia = $this->crearProvincia($cabeceras);

        if ($localidad === null) {
            $localidad = [
                'nombre' => 'Mercedes',
                'provincia_id' => $provincia['id']
            ];
        }

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/localidades", $localidad);

        return $respuesta->getData(true)['localidad'];
    }

    private function getLocalidad($id)
    {
        return Localidad::withTrashed()
            ->with('provincia')
            ->where('id_localidad', $id)
            ->firstOrFail()
            ->toArray();
    }

     /**
     * No debería obtener ninguna localidad
     */
    public function testNoDeberiaObtenerNingunaLocalidad()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $provincia = $this->crearProvincia($cabeceras);
        $id = $provincia['id'];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/provincias/$id/localidades");

        $estructura = $this->getEstructuraLocalidades();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['localidades' => []]);
    }

    /**
     * Debería obtener localidades
     */
    public function testDeberiaObtenerLocalidades()
    {
        factory(Localidad::class, 10)->create();

        $provincia = Provincia::inRandomOrder()->first();
        $id = $provincia->id;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/provincias/$id/localidades");

        $estructura = $this->getEstructuraLocalidades();
        $localidades = $provincia->localidades()->get()->toArray();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['localidades' => $localidades]);
    }

    /**
     * Debería crear una localidad
     */
    public function testDeberiaCrearUnaLocalidad()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $provincia = $this->crearProvincia($cabeceras);

        $localidad = [
            'nombre' => 'Mercedes',
            'provincia_id' => $provincia['id']
        ];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/localidades", $localidad);

        $estructura = $this->getEstructuraLocalidad();

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'localidad' => ['nombre' => 'Mercedes'],
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => 'La localidad Mercedes - Corrientes ha sido creada'
                ]
            ]);
    }

     /**
     * Debería obtener una localidad
     */
    public function testDeberiaObtenerUnaLocalidad()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $localidadGuardada = $this->crearLocalidad($cabeceras);
        $id = $localidadGuardada['id'];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/localidades/$id");

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($this->estructuraLocalidad)
            ->assertJson([
                'localidad' => $localidadGuardada
            ]);
    }

    /**
     * Debería editar una localidad
     */
    public function testDeberiaEditarUnaLocalidad()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $localidadGuardada = $this->crearLocalidad($cabeceras);
        $id = $localidadGuardada['id'];

        $localidadModificada = ['nombre' => 'Curuzú Cuatía'];
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('PUT', "api/localidades/$id", $localidadModificada);

        $localidadEsperada = array_merge($localidadGuardada, $localidadModificada);
        unset($localidadEsperada['updated_at']);

        $estructura = $this->getEstructuraLocalidad();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'localidad' => $localidadEsperada,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'La localidad Curuzú Cuatía - Corrientes ha sido modificada'
                ]
            ]);
    }

    /**
     * Debería eliminar una localidad
     */
    public function testDeberiaEliminarUnaLocalidad()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $localidadGuardada = $this->crearLocalidad($cabeceras);
        $id = $localidadGuardada['id'];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/localidades/$id");

        $localidadDB = $this->getLocalidad($id);
        $estructura = $this->getEstructuraLocalidad();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'localidad' => $localidadDB,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ELIMINADO',
                    'descripcion' => 'La localidad Mercedes - Corrientes ha sido eliminada'
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['localidad']['deleted_at'];
        $this->assertNotNull($deletedAt);
    }

    /**
     * Debería restaurar una localidad
     */
    public function testDeberiaRestaurarUnaLocalidad()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $localidadGuardada = $this->crearLocalidad($cabeceras);
        $id = $localidadGuardada['id'];

        $this->withHeaders($cabeceras)
            ->json('DELETE', "api/localidades/$id");

        $respuesta = $this->withHeaders($cabeceras)
            ->json('POST', "api/localidades/$id/restaurar");

        $localidadDB = $this->getLocalidad($id);
        $estructura = $this->getEstructuraLocalidad();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertExactJson([
                'localidad' => $localidadDB,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'RESTAURADO',
                    'descripcion' => 'La localidad Mercedes - Corrientes ha sido dada de alta'
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['localidad']['deleted_at'];
        $this->assertNull($deletedAt);
    }
}
