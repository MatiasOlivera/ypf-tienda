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
            'deleted_at'
        ]
    ];

    private function getEstructuraDomicilios()
    {
        return array_merge(['domicilios']);
    }

    private function getEstructuraDomicilio()
    {
        return array_merge($this->estructuraDomicilio, $this->estructuraMensaje);
    }

    private function getEstructuraDomicilioConLocalidad()
    {
        $estructura = $this->estructuraDomicilio;
        $estructura['domicilio']['localidad'] = [
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
        ];

        return array_merge($estructura, $this->estructuraMensaje);
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

        $estructura = $this->getEstructuraDomicilios();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertExactJson(['domicilios' => []]);
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

        $estructura = $this->getEstructuraDomicilios();
        $domicilios = $cliente->domicilios()->get()->toArray();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
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

        $estructura = $this->getEstructuraDomicilioConLocalidad();
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

    /**
     * Debería editar un domicilio
     */
    public function testDeberiaEditarUnDomicilio()
    {
        $domicilio = $this->crearDomicilio();
        $clienteId = $domicilio['cliente_id'];
        $id = $domicilio['id'];

        $domicilioModificado = array_merge($domicilio, ['calle' => 'San Martín']);

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('PUT', "api/clientes/$clienteId/domicilios/$id", $domicilioModificado);

        $domicilioEsperado = array_merge($domicilio, $domicilioModificado);
        unset($domicilioEsperado['updated_at']);
        $estructura = $this->getEstructuraDomicilio();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'domicilio' => $domicilioEsperado,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => "El domicilio San Martín {$domicilio['numero']} ha sido modificado"
                ]
            ]);
    }

    /**
     * Debería eliminar un domicilio
     */
    public function testDeberiaEliminarUnDomicilio()
    {
        $domicilio = $this->crearDomicilio();
        $clienteId = $domicilio['cliente_id'];
        $id = $domicilio['id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/clientes/$clienteId/domicilios/$id");

        $estructura = $this->getEstructuraDomicilio();
        unset($domicilio['updated_at']);

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'domicilio' => $domicilio,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ELIMINADO',
                    'descripcion' => "El domicilio {$domicilio['calle']} {$domicilio['numero']} ha sido eliminado"
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['domicilio']['deleted_at'];
        $this->assertNotNull($deletedAt);
    }

    /**
     * Debería restaurar un domicilio
     */
    public function testDeberiaRestaurarUnDomicilio()
    {
        $domicilio = $this->crearDomicilio();
        $clienteId = $domicilio['cliente_id'];
        $id = $domicilio['id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/clientes/$clienteId/domicilios/$id");

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/clientes/$clienteId/domicilios/$id/restaurar");

        $estructura = $this->getEstructuraDomicilio();
        unset($domicilio['updated_at']);

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'domicilio' => $domicilio,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'RESTAURADO',
                    'descripcion' => "El domicilio {$domicilio['calle']} {$domicilio['numero']} ha sido dado de alta"
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['domicilio']['deleted_at'];
        $this->assertNull($deletedAt);
    }
}
