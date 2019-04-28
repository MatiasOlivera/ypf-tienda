<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Cliente\Cliente\{ ClienteCreateRequest, ClienteUpdateRequest};

class ClientesController extends Controller
{
    protected $baseController;
    protected $modeloSingular;
    protected $modeloPlural;

    public function __Construct()
    {
        $this->modeloPlural = 'Clientes';
        $this->modeloSingular = 'Cliente';
        $this->baseController   = new BaseController($this->modeloSingular, $this->modeloPlural);
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = [
            'modelo'            => 'Cliente',
            'campos'            => ['id', 'nombre', 'documento', 'observacion', 'created_at', 'updated_at', 'deleted_at',],
            'relaciones'        => null,
            'buscar'            => $request->input("buscar", null),
            'eliminados'        => $request->input("eliminados", false),
            'paginado'  => [
                'porPagina'     => $request->input("porPagina", 10),
                'ordenadoPor'   => $request->input("ordenadoPor", 'cliente'),
                'orden'         => $request->input("orden", true),
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
        $inputs = $request->only('documneto', 'nombre', 'observacion');
        $nombre   = $request->input('nombre');
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
        $nombre  = $request->input('nombre');
        $inputs = $request->only('documneto', 'nombre', 'observacion');
        $parametros = [
            'inputs' => $inputs,
            'modelo' => $cliente,
        ];
        return $this->baseController->update($parametros, $nombre);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $nombre  = $cliente->nombre;
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
        $nombre  = $cliente->nombre;
        return $this->baseController->restore($cliente, $nombre);
    }
}
