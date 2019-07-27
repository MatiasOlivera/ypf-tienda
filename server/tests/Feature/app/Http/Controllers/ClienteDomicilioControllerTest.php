<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Cliente;
use Tests\TestCase;
use App\ClienteDomicilio;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class ClienteDomicilioControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EstructuraJsonHelper;

    private $estructuraDomicilio = [
        'domicilio' => [
            'id',
            'calle',
            'numero',
            'aclaracion',
            'localidad_id',
            'cliente_id',
            'created_at',
            'updated_at',
            'deleted_at',
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
        ]
    ];

    private function getEstructuraDomicilio()
    {
        return array_merge($this->estructuraDomicilio, $this->estructuraMensaje);
    }

    private function crearDomicilio()
    {
        return factory(ClienteDomicilio::class, 1)->create()->toArray()[0];
    }

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

    /**
     * Debería crear un domicilio
     */
    public function testDeberiaCrearUnDomicilio()
    {
        $domicilio = factory(ClienteDomicilio::class, 1)->make()->toArray()[0];
        $id = $domicilio['cliente_id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/clientes/$id/domicilios", $domicilio);

        $estructura = $this->getEstructuraDomicilio();
        unset($domicilio['id']);

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'domicilio' => $domicilio,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => "El domicilio {$domicilio['calle']} {$domicilio['numero']} ha sido creado"
                ]
            ]);
    }

    /**
     * Debería obtener un domicilio
     */
    public function testDeberiaObtenerUnDomicilio()
    {
        $domicilio = $this->crearDomicilio();
        $clienteId = $domicilio['cliente_id'];
        $id = $domicilio['id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$clienteId/domicilios/$id");

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($this->estructuraDomicilio)
            ->assertJson([
                'domicilio' => $domicilio
            ]);
    }
}
