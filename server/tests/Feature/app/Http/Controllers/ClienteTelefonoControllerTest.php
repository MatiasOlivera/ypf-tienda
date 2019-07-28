<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Cliente;
use Tests\TestCase;
use App\ClienteTelefono;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class ClienteTelefonoControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EstructuraJsonHelper;

    private function getEstructuraTelefonos()
    {
        return array_merge(['telefonos'], $this->estructuraPaginacion);
    }

    /**
     * No debería obtener ningún teléfono
     */
    public function testNoDeberiaObtenerNingunTelefono()
    {
        $cliente = factory(Cliente::class, 1)->create()->toArray()[0];
        $id = $cliente['id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$id/telefonos");

        $estructura = $this->getEstructuraTelefonos();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertExactJson(['telefonos' => []]);
    }

    /**
     * Debería obtener telefonos
     */
    public function testDeberiaObtenerTelefonos()
    {
        factory(ClienteTelefono::class, 2)->create();

        $cliente = Cliente::inRandomOrder()->first();
        $id = $cliente->id;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$id/telefonos");

        $estructura = $this->getEstructuraTelefonos();
        $telefonos = $cliente->telefonos()->get()->toArray();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['telefonos' => $telefonos]);
    }
}
