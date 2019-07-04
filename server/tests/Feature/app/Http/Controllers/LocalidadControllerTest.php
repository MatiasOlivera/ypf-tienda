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

    private $estructuraLocalidad= [
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
        $id = $provincia['id'];

        if ($localidad === null) {
            $localidad = ['nombre' => 'Mercedes'];
        }

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/provincias/$id/localidades", $localidad);

        return $respuesta->getData(true)['localidad'];
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

        $respuesta
            ->assertOk()
            ->assertJson([
                'localidades' => [
                    'data' => []
                ]
            ]);
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

        $localidades = $provincia->localidades()->get()->toArray();

        $respuesta
            ->assertOk()
            ->assertJson([
                'localidades' => [
                    'data' => $localidades
                ]
            ]);
    }

    /**
     * Debería crear una localidad
     */
    public function testDeberiaCrearUnaLocalidad()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $provincia = $this->crearProvincia($cabeceras);
        $idProvincia = $provincia['id'];

        $localidad = ['nombre' => 'Mercedes'];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/provincias/$idProvincia/localidades", $localidad);

        /* TODO: seleccionar todas las columnas de la tabla */
        $estructura = array_merge([
            'localidad' => [
                'id',
                'nombre',
                'provincia_id',
                'created_at',
                'updated_at',
                // 'deleted_at'
            ]
        ], $this->estructuraMensaje);

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
            ->json('GET', "api/provincias/localidades/$id");

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($this->estructuraLocalidad)
            ->assertJson([
                'localidad' => $localidadGuardada
            ]);
    }
}
