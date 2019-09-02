<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Cliente;
use Tests\TestCase;
use App\ClienteRazonSocial;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class ClienteRazonSocialControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EstructuraJsonHelper;

    private function getEstructuraRazones()
    {
        return array_merge(['razonesSociales']);
    }

    /**
     * No debería obtener ninguna razon social
     */
    public function testNoDeberiaObtenerNingunaRazonSocial()
    {
        $cliente = factory(Cliente::class, 1)->create()->toArray()[0];
        $id = $cliente['id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$id/razones");

        $estructura = $this->getEstructuraRazones();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertExactJson(['razonesSociales' => []]);
    }

    /**
     * Debería obtener razones sociales
     */
    public function testDeberiaObtenerRazonesSociales()
    {
        $cliente = factory(Cliente::class)->states('razonesSociales')->create();
        $id = $cliente->id;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$id/razones");

        $estructura = $this->getEstructuraRazones();
        $razones = $cliente->razones()->get()->toArray();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['razonesSociales' => $razones]);
    }
}
