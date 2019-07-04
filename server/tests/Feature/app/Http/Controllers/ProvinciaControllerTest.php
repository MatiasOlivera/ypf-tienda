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
}
