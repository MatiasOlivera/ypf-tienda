<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Provincia;
use Tests\TestCase;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class ProvinciaControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EstructuraJsonHelper;

    private $estructuraProvincia = [
        'provincia' => [
            'id',
            'nombre',
            'created_at',
            'updated_at',
            'deleted_at'
        ]
    ];

    private function getEstructuraProvincia()
    {
        return array_merge($this->estructuraProvincia, $this->estructuraMensaje);
    }

    private function crearProvincia($cabeceras, $provincia = null)
    {
        if ($provincia === null) {
            $provincia = ['nombre' => 'Corrientes'];
        }

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/provincias', $provincia);

        return $respuesta->getData(true)['provincia'];
    }

    /**
     * No debería obtener ninguna provincia
     */
    public function testNoDeberiaObtenerNingunaProvincia()
    {
        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/provincias');

         $respuesta
            ->assertOk()
            ->assertJsonStructure(['provincias'])
            ->assertJson(['provincias' => []]);
    }

    /**
     * Debería obtener las provincias
     */
    public function testDeberiaObtenerLasProvincias()
    {
        factory(Provincia::class, 10)->create();

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/provincias');

        $provincias = Provincia::all()->toArray();

        $respuesta
            ->assertOk()
            ->assertJsonStructure(['provincias'])
            ->assertJson(['provincias' => $provincias]);
    }

    /**
     * Debería crear una provincia
     */
    public function testDeberiaCrearUnaProvincia()
    {
        $provincia = ['nombre' => 'Corrientes'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/provincias', $provincia);

        /* TODO: seleccionar todas las columnas de la tabla */
        $estructura = array_merge([
            'provincia' => [
                'id',
                'nombre',
                'created_at',
                'updated_at',
                // 'deleted_at'
            ]
        ], $this->estructuraMensaje);

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'provincia' => $provincia,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => 'La provincia Corrientes ha sido creada'
                ]
            ]);
    }

    /**
     * Debería obtener una provincia
     */
    public function testDeberiaObtenerUnaProvincia()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $provinciaGuardada = $this->crearProvincia($cabeceras);
        $id = $provinciaGuardada['id'];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/provincias/$id");

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($this->estructuraProvincia)
            ->assertJson([
                'provincia' => $provinciaGuardada
            ]);
    }

    /**
     * Debería editar una provincia
     */
    public function testDeberiaEditarUnaProvincia()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $provinciaGuardada = $this->crearProvincia($cabeceras);
        $id = $provinciaGuardada['id'];

        $provinciaModificada = ['nombre' => 'Buenos Aires'];
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('PUT', "api/provincias/$id", $provinciaModificada);

        $provinciaEsperada = array_merge($provinciaGuardada, $provinciaModificada);
        unset($provinciaEsperada['updated_at']);
        $estructura = $this->getEstructuraProvincia();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'provincia' => $provinciaEsperada,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'La provincia Buenos Aires ha sido modificada'
                ]
            ]);
    }

     /**
     * Debería eliminar una provincia
     */
    public function testDeberiaEliminarUnaProvincia()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $provinciaGuardada = $this->crearProvincia($cabeceras);
        $id = $provinciaGuardada['id'];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/provincias/$id");

        $provinciaDB = Provincia::withTrashed()
            ->where('id_provincia', $id)
            ->firstOrFail()
            ->toArray();
        $estructura = $this->getEstructuraProvincia();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'provincia' => $provinciaDB,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ELIMINADO',
                    'descripcion' => 'La provincia Corrientes ha sido eliminada'
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['provincia']['deleted_at'];
        $this->assertNotNull($deletedAt);
    }

    /**
     * Debería restaurar una provincia
     */
    public function testDeberiaRestaurarUnaProvincia()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $provinciaGuardada = $this->crearProvincia($cabeceras);
        $id = $provinciaGuardada['id'];

        $this->withHeaders($cabeceras)
            ->json('DELETE', "api/provincias/$id");

        $respuesta = $this->withHeaders($cabeceras)
            ->json('POST', "api/provincias/$id/restaurar");

        $provinciaDB = Provincia::withTrashed()
            ->where('id_provincia', $id)
            ->firstOrFail()
            ->toArray();
        $estructura = $this->getEstructuraProvincia();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertExactJson([
                'provincia' => $provinciaDB,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'RESTAURADO',
                    'descripcion' => 'La provincia Corrientes ha sido dada de alta'
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['provincia']['deleted_at'];
        $this->assertNull($deletedAt);
    }
}
