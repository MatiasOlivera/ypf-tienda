<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Cliente;
use Tests\TestCase;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientesControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;

    // TODO: usar EstructuraJsonHelper en vez de definir $estructuraPaginacion
    // y $estructuraMensaje
    protected $estructuraPaginacion = [
        'paginacion' => [
            'total',
            'porPagina',
            'paginaActual',
            'ultimaPagina',
            'desde',
            'hasta',
            'rutas' => [
                'primeraPagina',
                'ultimaPagina',
                'siguientePagina',
                'paginaAnterior',
                'base'
            ]
        ]
    ];

    protected $estructuraMensaje = [
        'mensaje' => ['tipo', 'codigo', 'descripcion']
    ];

    private $estructuraCliente = [
        'cliente' => [
            'id',
            'nombre',
            'documento',
            'observacion',
            'created_at',
            'updated_at',
            'deleted_at'
        ]
    ];

    private function getEstructuraClientes()
    {
        return array_merge(['clientes'], $this->estructuraPaginacion);
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

        /* TODO: seleccionar todas las columnas de la tabla */
        $estructura = array_merge([
            'cliente' => [
                'id',
                'nombre',
                'documento',
                'observacion',
                'created_at',
                'updated_at',
                // 'deleted_at'
            ]
        ], $this->estructuraMensaje);

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
            ->assertJsonStructure($this->estructuraCliente)
            ->assertJson([
                'cliente' => $clienteGuardado
            ]);
    }
}
