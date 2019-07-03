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

    private function getEstructuraClientes()
    {
        return array_merge(['clientes'], $this->estructuraPaginacion);
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
}
