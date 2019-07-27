<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Cliente;
use Tests\TestCase;
use App\ClienteDomicilio;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteDomicilioControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;

    /**
     * No debería obtener ningún domicilio
     */
    public function testNoDeberiaObtenerNingunDomicilio()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $cliente = factory(Cliente::class, 1)->create()->toArray()[0];
        $id = $cliente['id'];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$id/domicilios");

        $respuesta
            ->assertOk()
            ->assertJson(['domicilios' => []]);
    }

    /**
     * Debería obtener domicilios
     */
    public function testDeberiaObtenerDomicilios()
    {
        factory(ClienteDomicilio::class, 2)->create();

        $cliente = Cliente::inRandomOrder()->first();
        $id = $cliente->id;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$id/domicilios");

        $domicilios = $cliente->domicilios()->get()->toArray();

        $respuesta
            ->assertOk()
            ->assertJson(['domicilios' => $domicilios]);
    }
}
