<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Cliente;
use Tests\ApiTestCase;
use AutorizacionSeeder;
use App\ClienteTelefono;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class ClienteTelefonoControllerTest extends ApiTestCase
{
    use AuthHelper;
    use WithFaker;
    use RefreshDatabase;
    use EloquenceSolucion;
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

    protected $usuario;
    protected $cabeceras;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoSuperAdministrador();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];
    }

    private function getEstructuraTelefonos()
    {
        return array_merge(['telefonos']);
    }

    private function getEstructuraTelefono()
    {
        return array_merge($this->estructuraTelefono, $this->estructuraMensaje);
    }

    private function crearTelefono()
    {
        return factory(ClienteTelefono::class, 1)->create()->toArray()[0];
    }

    private function crearTelefonoConNombre()
    {
        return factory(ClienteTelefono::class, 1)->create([
            'nombreContacto' => $this->faker->firstName()
        ])->toArray()[0];
    }

    /**
     * No debería obtener ningún teléfono
     */
    public function testNoDeberiaObtenerNingunTelefono()
    {
        $cliente = factory(Cliente::class, 1)->create()->toArray()[0];
        $id = $cliente['id'];

        $respuesta = $this
            ->withHeaders($this->cabeceras)
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

        $respuesta = $this
            ->withHeaders($this->cabeceras)
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

        $respuesta = $this
            ->withHeaders($this->cabeceras)
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

        $respuesta = $this
            ->withHeaders($this->cabeceras)
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

        $respuesta = $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/$clienteId/telefonos/$id");

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($this->estructuraTelefono)
            ->assertJson([
                'telefono' => $telefono
            ]);
    }

    /**
     * Debería editar un teléfono
     */
    public function testDeberiaEditarUnTelefono()
    {
        $telefono = $this->crearTelefono();
        $clienteId = $telefono['cliente_id'];
        $id = $telefono['id'];

        $telefonoModificado = factory(ClienteTelefono::class, 1)->make([
            'nombreContacto' => $this->faker->firstName()
        ])->toArray()[0];
        unset($telefonoModificado['cliente_id']);

        $respuesta = $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/clientes/$clienteId/telefonos/$id", $telefonoModificado);

        $estructura = $this->getEstructuraTelefono();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'telefono' => $telefonoModificado,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => "El teléfono de {$telefonoModificado['nombreContacto']} ha sido modificado"
                ]
            ]);
    }

    /**
     * Debería eliminar un teléfono
     */
    public function testDeberiaEliminarUnTelefono()
    {
        $telefono = $this->crearTelefonoConNombre();
        $clienteId = $telefono['cliente_id'];
        $id = $telefono['id'];

        $respuesta = $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/clientes/$clienteId/telefonos/$id");

        unset($telefono['updated_at']);
        $estructura = $this->getEstructuraTelefono();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'telefono' => $telefono,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ELIMINADO',
                    'descripcion' => "El teléfono de {$telefono['nombreContacto']} ha sido eliminado"
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['telefono']['deleted_at'];
        $this->assertNotNull($deletedAt);
    }

    /**
     * Debería restaurar un teléfono
     */
    public function testDeberiaRestaurarUnTelefono()
    {
        $telefono = $this->crearTelefonoConNombre();
        $clienteId = $telefono['cliente_id'];
        $id = $telefono['id'];

        $respuesta = $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/clientes/$clienteId/telefonos/$id");

        $respuesta = $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes/$clienteId/telefonos/$id/restaurar");

        unset($telefono['updated_at']);
        $estructura = $this->getEstructuraTelefono();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'telefono' => $telefono,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'RESTAURADO',
                    'descripcion' => "El teléfono de {$telefono['nombreContacto']} ha sido dado de alta"
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['telefono']['deleted_at'];
        $this->assertNull($deletedAt);
    }
}
