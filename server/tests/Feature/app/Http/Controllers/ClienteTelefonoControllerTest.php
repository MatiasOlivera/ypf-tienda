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
    use WithFaker;
    use RefreshDatabase;
    use EstructuraJsonHelper;

    private $estructuraTelefono = [
        'telefono' => [
            'id',
            'area',
            'telefono',
            'nombreContacto',
            'cliente_id',
            'created_at',
            'updated_at',
            'deleted_at'
        ]
    ];

    private function getEstructuraTelefonos()
    {
        return array_merge(['telefonos'], $this->estructuraPaginacion);
    }

    private function getEstructuraTelefono()
    {
        return array_merge($this->estructuraTelefono, $this->estructuraMensaje);
    }
    private function crearTelefono()
    {
        return factory(ClienteTelefono::class, 1)->create()->toArray()[0];
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

    /**
     * Debería crear un teléfono con nombre de contacto
     */
    public function testDeberiaCrearUnTelefonoConNombre()
    {
        $telefono = factory(ClienteTelefono::class, 1)->make([
            'nombreContacto' => $this->faker->firstName()
        ])->toArray()[0];
        $id = $telefono['cliente_id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/clientes/$id/telefonos", $telefono);

        $estructura = $this->getEstructuraTelefono();
        unset($telefono['id']);

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'telefono' => $telefono,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => "El teléfono de {$telefono['nombreContacto']} ha sido creado"
                ]
            ]);

        $nombreContacto = $respuesta->getData(true)['telefono']['nombreContacto'];
        $this->assertEquals($telefono['nombreContacto'], $nombreContacto);
    }

     /**
     * Debería crear un teléfono sin nombre de contacto
     */
    public function testDeberiaCrearUnTelefonoSinNombre()
    {
        $telefono = factory(ClienteTelefono::class, 1)->make()->toArray()[0];
        $id = $telefono['cliente_id'];

        unset($telefono['nombreContacto']);

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/clientes/$id/telefonos", $telefono);

        $estructura = $this->getEstructuraTelefono();
        unset($telefono['id']);

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'telefono' => $telefono,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => "El teléfono {$telefono['area']}-{$telefono['telefono']} ha sido creado"
                ]
            ]);

        $nombreContacto = $respuesta->getData(true)['telefono']['nombreContacto'];
        $this->assertNull($nombreContacto);
    }

    /**
     * Debería obtener un teléfono
     */
    public function testDeberiaObtenerUnTelefono()
    {
        $telefono = $this->crearTelefono();
        $clienteId = $telefono['cliente_id'];
        $id = $telefono['id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$clienteId/telefonos/$id");

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($this->estructuraTelefono)
            ->assertJson([
                'telefono' => $telefono
            ]);
    }
}
