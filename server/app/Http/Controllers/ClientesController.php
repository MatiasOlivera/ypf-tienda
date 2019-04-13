<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Cliente\Cliente\ClienteCreateRequest;
use App\Http\Requests\Cliente\Cliente\ClienteUpdateRequest;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = [
            'modelo' => 'Cliente',
            'campos' => ['id_cliente', 'dni', 'cliente', 'obsevacion', 'otros', 'created_at', 'updated_at', 'deleted_at',],
            'relaciones' => null,
            'buscar' => $request->input("buscar", null),
            'eliminados' => $request->input("eliminados", false),
            'paginado' => [
                'porPagina'   => $request->input("porPagina", 10),
                'ordenadoPor' => $request->input("ordenadoPor", 'cliente'),
                'orden'       => $request->input("orden", true),
            ]
        ];

        //mensajes
        $mensajes = [
            'error' => [
                'descripcion' => 'Hemos tenido un error durante la consulta de datos, intente nuevamente',
                'codigo'      => 'CLIENTE_INDEX_CONTROLLER',
            ],
            'exito' => [
                'descripcion' => 'operacion exitosa',
                'codigo'      => 'CLIENTE_CATCH_INDEX_CONTROLLER',
            ]
        ];

        $BaseController   = new BaseController;
        return $BaseController->index($parametros, $mensajes);
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

        //parametros
        $parametros = [
            'inputs' => $inputs,
            'modelo' => 'Cliente',
        ];

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'CLIENTE_STORE_CONTROLLER',
                'descripcion' => "{$nombre} se ha creado con exito",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar guardar a {$nombre}",
                'codigo' => 'CLIENTE_CATCH_STORE_CONTROLLER'
            ],
        ];

        $BaseController   = new BaseController();
        return $BaseController->store($parametros, $mensaje);
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        return $cliente;
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

        //mensajes
        $nombreNew  = $request->input('nombre');
        $nombreOld  = $cliente->nombre;
        $mensaje = [
            'exito' => [
                'codigo' => 'CLIENTE_UPDATE_CONTROLLER',
                'descripcion' => "{$nombreNew} se ha modificado",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar modificar el cliente {$nombreOld}",
                'codigo' => 'CATCH_CLIENTE_UPDATE_CONTROLLER'
            ],
        ];

        $inputs = $request->only('documneto', 'nombre', 'observacion');
        $parametros = [
            'inputs' => $inputs,
            'modelo' => $cliente - fill($inputs)->save(),
        ];
        $metodo  = new BaseController();
        return $metodo->update($parametros, $mensaje);
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

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'CLIENTE_DESTROY_CONTROLLER',
                'descripcion' => "{$nombre} ha sido eliminado",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar eliminar a {$nombre}",
                'codigo' => 'CLIENTE_DESTROY_CONTROLLER'
            ],
        ];

        $BaseController  = new BaseController();
        return $BaseController->destroy($cliente, $mensaje);
    }

    /**
     * Restaurar el cliente que ha sido eliminado
     *
     * @param   App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function restore(Cliente $cliente)
    {
        //mensajes
        $nombre  = $cliente->nombre;
        $mensaje = [
            'exito' => [
                'codigo' => 'CLIENTE_RESTORE_CONTROLLER',
                'descripcion' => "{$nombre} ha sido dado de alta",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar dar de alta a {$nombre}",
                'codigo' => 'CLIENTE_RESTORE_CONTROLLER'
            ],
        ];

        $BaseController  = new BaseController();
        return $BaseController->restore($cliente, $mensaje);
    }
}
