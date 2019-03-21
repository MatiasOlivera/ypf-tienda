<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Http\Controllers\BaseController;

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
            'campos' => ['id_cliente', 'dni', 'cliente', 'obsevacion', 'otros', 'created_at', 'updated_at', 'deleted_at', ],
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $BaseController   = new BaseController();
        $inputs   = $request->only('dni', 'cliente', 'obsevacion');
        $nombre   = $request->input('cliente');

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
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        $inputs  = $request->only('dni', 'cliente', 'obsevacion');
        $nombre  = $request->input('cliente');
        $parametros = [
            'inputs' => $inputs,
            'modelo' => $cliente,
        ];

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'CLIENTE_UPDATE_CONTROLLER',
                'descripcion' => "{$nombre} se ha modificado",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar modificar el cliente {$nombre}",
                'codigo' => 'CATCH_CLIENTE_UPDATE_CONTROLLER'
            ],
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
        $nombre  = $cliente->cliente;
        $BaseController  = new BaseController();

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

        return $BaseController->destroy($cliente, $mensaje);
    }

    /**
     * Restaurar el usuario que ha sido eliminado
     *
     * @param   App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function restore(Cliente $cliente)
    {
        $nombre  = $cliente->cliente;
        $BaseController  = new BaseController();

        //mensajes
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

        return $BaseController->restore($cliente, $mensaje);
    }
}
