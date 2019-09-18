<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Cliente;
use Tests\TestCase;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Tests\Feature\Utilidades\EstructuraCliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class ClientesControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EstructuraCliente;
    use EloquenceSolucion;
    use EstructuraJsonHelper;

    private function getEstructuraClientes()
    {
        return array_merge(['clientes'], $this->estructuraPaginacion);
    }

    private function getEstructuraCliente()
    {
        return array_merge(['cliente' => $this->atributosCliente], $this->estructuraMensaje);
    }

    private function crearCliente($cabeceras, $cliente = null)
    {
        if ($cliente === null) {
            $cliente = [
                'nombre' => 'Juan Perez',
                'documento' => 12345678
            ];
        }

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/clientes', $cliente);

        return $respuesta->getData(true)['cliente'];
    }

    /**
     * No debería obtener ningún cliente
     */
    public function testNoDeberiaObtenerNingunCliente()
    {
        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/clientes');

        $estructura = $this->getEstructuraClientes();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['clientes' => []]);
    }

    /**
     * Debería obtener clientes
     */
    public function testDeberiaObtenerClientes()
    {
        factory(Cliente::class, 10)->create();

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/clientes');

        $estructura = $this->getEstructuraClientes();
        $clientes = Cliente::orderBy('nombre', 'ASC')->get()->toArray();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['clientes' => $clientes]);
    }

    /**
     * Debería crear un cliente
     */
    public function testDeberiaCrearUnCliente()
    {
        $cliente = [
            'nombre' => 'Juan Perez',
            'documento' => 12345678
        ];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/clientes', $cliente);

        $estructura = $this->getEstructuraCliente();

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'cliente' => $cliente,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => 'El cliente Juan Perez ha sido creado'
                ]
            ]);
    }

    /**
     * Debería obtener un cliente
     */
    public function testDeberiaObtenerUnCliente()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $clienteGuardado = $this->crearCliente($cabeceras);
        $id = $clienteGuardado['id'];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$id");

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure(['cliente' => $this->atributosCliente])
            ->assertJson([
                'cliente' => $clienteGuardado
            ]);
    }

    /**
     * Debería editar un cliente
     */
    public function testDeberiaEditarUnCliente()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $clienteGuardado = $this->crearCliente($cabeceras);
        $id = $clienteGuardado['id'];

        $clienteModificado = [
            'nombre' => 'Juan Manuel Perez',
            'documento' => 12345678
        ];
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('PUT', "api/clientes/$id", $clienteModificado);

        $clienteEsperado = array_merge($clienteGuardado, $clienteModificado);
        unset($clienteEsperado['updated_at']);
        $estructura = $this->getEstructuraCliente();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'cliente' => $clienteEsperado,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'El cliente Juan Manuel Perez ha sido modificado'
                ]
            ]);
    }

    /**
     * Debería eliminar una cliente
     */
    public function testDeberiaEliminarUnCliente()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $clienteGuardado = $this->crearCliente($cabeceras);
        $id = $clienteGuardado['id'];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/clientes/$id");

        $clienteDB = Cliente::withTrashed()
            ->where('id_cliente', $id)
            ->firstOrFail()
            ->toArray();

        $estructura = $this->getEstructuraCliente();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'cliente' => $clienteDB,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ELIMINADO',
                    'descripcion' => 'El cliente Juan Perez ha sido eliminado'
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['cliente']['deleted_at'];
        $this->assertNotNull($deletedAt);
    }

    /**
     * Debería restaurar un cliente
     */
    public function testDeberiaRestaurarUnCliente()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $clienteGuardado = $this->crearCliente($cabeceras);
        $id = $clienteGuardado['id'];

        $this->withHeaders($cabeceras)
            ->json('DELETE', "api/clientes/$id");

        $respuesta = $this->withHeaders($cabeceras)
            ->json('POST', "api/clientes/$id/restaurar");

        $clienteDB = Cliente::withTrashed()
            ->where('id_cliente', $id)
            ->firstOrFail()
            ->toArray();

        $estructura = $this->getEstructuraCliente();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertExactJson([
                'cliente' => $clienteDB,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'RESTAURADO',
                    'descripcion' => 'El cliente Juan Perez ha sido dado de alta'
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['cliente']['deleted_at'];
        $this->assertNull($deletedAt);
    }
}
