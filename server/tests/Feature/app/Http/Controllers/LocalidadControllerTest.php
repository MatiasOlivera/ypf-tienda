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

    private function crearProvincia($cabeceras)
    {
        $provincia = ['nombre' => 'Corrientes'];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/provincias', $provincia);

        return $respuesta->getData(true)['provincia'];
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
}
