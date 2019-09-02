<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Cliente;
use Tests\TestCase;
use App\ClienteRazonSocial;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;
use Tests\Feature\Utilidades\AtributosClienteRazonSocial;

class ClienteRazonSocialControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EstructuraJsonHelper;
    use AtributosClienteRazonSocial;

    private function getEstructuraRazones()
    {
        return array_merge(['razonesSociales']);
    }

    private function getEstructuraRazonSocial()
    {
        return array_merge(
            ['razonSocial' => $this->atributosClienteRazonSocial],
            $this->estructuraMensaje
        );
    }

    /**
     * No debería obtener ninguna razon social
     */
    public function testNoDeberiaObtenerNingunaRazonSocial()
    {
        $cliente = factory(Cliente::class)->create();
        $id = $cliente->id;

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
        $razones = $cliente->razonesSociales()->get()->toArray();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['razonesSociales' => $razones]);
    }

    public function test_deberia_crear_una_razon_social()
    {
        $cliente = factory(Cliente::class)->create();
        $id = $cliente->id;

        $razonSocial = factory(ClienteRazonSocial::class)->make()->toArray();

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/clientes/$id/razones", $razonSocial);

        $estructura = $this->getEstructuraRazonSocial();
        unset($razonSocial['id']);

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'razonSocial' => $razonSocial,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => "La razón social {$razonSocial['denominacion']} ha sido creada"
                ]
            ]);
    }
}
