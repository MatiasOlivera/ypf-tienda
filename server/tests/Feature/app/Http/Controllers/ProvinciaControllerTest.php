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
}
