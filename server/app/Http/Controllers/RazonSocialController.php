<?php

namespace App\Http\Controllers;

use App\RazonSocial;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class RazonSocialController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = [
            'modelo' => 'RazonSocial',
            'campos' => ['id_razon', 'nombre', 'cuit', 'id_loc', 'calle', 'altura', 'area_tel', 'tel', 'mail', 'created_at', 'updated_at', 'deleted_at', ],
            'relaciones' => null,
            'buscar' => $request->input("buscar", null),
            'eliminados' => $request->input("eliminados", false),
            'paginado' => [
                'porPagina'   => $request->input("porPagina", 10),
                'ordenadoPor' => $request->input("ordenadoPor", 'nombre'),
                'orden'       => $request->input("orden", true),
            ]
        ];

        //mensajes
        $mensajes = [
            'error' => [
                'descripcion' => 'Hemos tenido un error durante la consulta de datos, intente nuevamente',
                'codigo'      => 'RAZON_INDEX_CONTROLLER',
            ],
            'exito' => [
                'descripcion' => 'operacion exitosa',
                'codigo'      => 'RAZON_CATCH_INDEX_CONTROLLER',
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
        $nombre  = $request->input('denominacion');
        $parametros = [
            'inputs' => [
                'nombre'    => $request->input('denominacion'),
                'cuit'      => $request->input('CUIT'),
                'id_loc'    => $request->input('localidad_id'),
                'calle'     => $request->input('calle'),
                'altura'    => $request->input('altura'),
                'area_tel'  => $request->input('area'),
                'tel'       => $request->input('telefono'),
                'mail'      => $request->input('mail'),
            ],
            'modelo' => 'RazonSocial',
        ];

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'RAZON_STORE_CONTROLLER',
                'descripcion' => " {$nombre} se ha creado con exito",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar guardar {$nombre}",
                'codigo' => 'CATCH_RAZON_STORE'
            ],
        ];

        $BaseController = new BaseController();
        return $BaseController->store($parametros, $mensaje);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function show(RazonSocial $razonSocial)
    {
        return $razonSocial;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RazonSocial $razonSocial)
    {
        $nombre  = $request->input('denominacion');
        $parametros = [
            'inputs' => [
                'nombre'    => $request->input('denominacion'),
                'cuit'      => $request->input('CUIT'),
                'id_loc'    => $request->input('localidad_id'),
                'calle'     => $request->input('calle'),
                'altura'    => $request->input('altura'),
                'area_tel'  => $request->input('area'),
                'tel'       => $request->input('telefono'),
                'mail'      => $request->input('mail'),
            ],
            'modelo' => $razonSocial,
        ];

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'RAZON_UPDATE_CONTROLLER',
                'descripcion' => " {$nombre} se ha modificado con exito",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar modificado a {$nombre}",
                'codigo' => 'CATCH_RAZON_UPDATE'
            ],
        ];

        $BaseController = new BaseController();
        return $BaseController->store($parametros, $mensaje);
    }

    /**
     * Restaurar el usuario que ha sido eliminado
     *
     * @param  \App\RazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function restore(RazonSocial $razonSocial)
    {
        $nombre  = $razonSocial->nombre;
        $BaseController  = new BaseController();

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'RAZON_RESTORE_CONTROLLER',
                'descripcion' => "{$nombre} ha sido dado de alta",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar dar de alta a {$nombre}",
                'codigo' => 'CATCH_RAZON_RESTORE'
            ],
        ];

        return $BaseController->restore($razonSocial, $mensaje);
    }
}
