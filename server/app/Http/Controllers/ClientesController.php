<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Cliente\Cliente\{ClientesRequest, ClienteCreateRequest, ClienteUpdateRequest};

class ClientesController extends Controller
{
    protected $baseController;
    protected $modeloSingular;
    protected $modeloPlural;
    protected $generoModelo;

    public function __construct()
    {
        $this->authorizeResource(Cliente::class, 'cliente');

        $this->modeloPlural = 'clientes';
        $this->modeloSingular = 'cliente';
        $this->generoModelo = 'masculino';
        $this->baseController   = new BaseController($this->modeloSingular, $this->modeloPlural, $this->generoModelo);
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\ClientesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(ClientesRequest $request)
    {
        $this->authorize('index', Cliente::class);

        $parametros = [
            'modelo'            => 'Cliente',
            'campos'            => [
                'id',
                'nombre',
                'documento',
                'observacion',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
            'relaciones'        => null,
            'buscar'            => $request->input("buscar", null),
            'eliminados'        => $request->input("eliminados", false),
            'paginado'  => [
                'porPagina'     => $request->input("porPagina", 10),
                'ordenarPor'   => $request->input("ordenarPor", 'nombre'),
                'orden'         => $request->input("orden", 'ASC'),
            ]
        ];

        return $this->baseController->index($parametros, $this->modeloPlural);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Cliente\Cliente\ClienteCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClienteCreateRequest $request)
    {
        $inputs = $request->only('documento', 'nombre', 'observacion');
        $nombre   = "El cliente {$request->input('nombre')}";
        $parametros = [
            'inputs' => $inputs,
            'modelo' =>  $this->modeloSingular,
        ];
        return $this->baseController->store($parametros, $nombre);
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        return $this->baseController->show($cliente);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Cliente\Cliente\ClienteUpdateRequest  $request
     * @param  App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteUpdateRequest $request, Cliente $cliente)
    {
        $nombres = [
            "exito" => "El cliente {$request->input('nombre')}",
            "error" => "El cliente {$cliente->nombre}"
        ];
        $inputs = $request->only('documento', 'nombre', 'observacion');
        $parametros = [
            'inputs' => $inputs,
            'instancia' => $cliente,
        ];
        return $this->baseController->update($parametros, $nombres);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $nombre = "El cliente $cliente->nombre";
        return $this->baseController->destroy($cliente, $nombre);
    }

    /**
     * Restaurar el cliente que ha sido eliminado
     *
     * @param   App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function restore(Cliente $cliente)
    {
        $this->authorize('restore', $cliente);

        $nombre = "El cliente $cliente->nombre";
        return $this->baseController->restore($cliente, $nombre);
    }
}
