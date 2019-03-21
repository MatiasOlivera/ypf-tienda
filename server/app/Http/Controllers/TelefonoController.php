<?php

namespace App\Http\Controllers;

use App\Telefono;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class TelefonoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = [
            'modelo' => 'Telefono',
            'campos' => ['id', 'id_cliente', 'area', 'tel', 'nombre_contacto', 'created_at', 'updated_at', 'deleted_at', ],
            'relaciones' => null,
            'buscar' => $request->input("buscar", null),
            'eliminados' => $request->input("eliminados", false),
            'paginado' => [
                'porPagina'   => $request->input("porPagina", 10),
                'ordenadoPor' => $request->input("ordenadoPor", 'nombre_contacto'),
                'orden'       => $request->input("orden", true),
            ]
        ];

        //mensajes
        $mensajes = [
            'error' => [
                'descripcion' => 'Hemos tenido un error durante la consulta de datos, intente nuevamente',
                'codigo'      => 'TELEFONO_INDEX_CONTROLLER',
            ],
            'exito' => [
                'descripcion' => 'operacion exitosa',
                'codigo'      => 'TELEFONO_CATCH_INDEX_CONTROLLER',
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
        $nombre  = $request->input('nombreContacto');
        $parametros = [
            'inputs' => [
                'id_cliente'       => $request->input('cliente'),
                'area'             => $request->input('area'),
                'tel'              => $request->input('telefono'),
                'nombre_contacto'  => $request->input('nombreContacto'),
            ],
            'modelo' => 'Telefono',
        ];

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'TELEFONO_STORE_CONTROLLER',
                'descripcion' => "El contacto {$nombre} se ha creado con exito",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar guardar el N° de {$nombre}",
                'codigo' => 'CATCH_TELEFONO_STORE'
            ],
        ];

        $BaseController = new BaseController();
        return $BaseController->store($parametros, $mensaje);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Telefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function show(Telefono $telefono)
    {
        return $telefono;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Telefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Telefono $telefono)
    {
        $nombre  = $request->input('nombreContacto');
        $parametros = [
            'inputs' => [
                'id_cliente'       => $request->input('cliente'),
                'area'             => $request->input('area'),
                'tel'              => $request->input('telefono'),
                'nombre_contacto'  => $request->input('nombreContacto'),
            ],
            'modelo' => $telefono,
        ];

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'TELEFONO_STORE_CONTROLLER',
                'descripcion' => "El contacto {$nombre} se ha modificado",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar modificar el contacto {$nombre}",
                'codigo' => 'CATCH_TELEFONO_UPDATE'
            ],
        ];

        $BaseController  = new BaseController();
        return $BaseController->update($parametros, $mensaje);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Telefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function destroy(Telefono $telefono)
    {
        $nombre  = $telefono->nombre_contacto;
        $BaseController  = new BaseController();

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'TELEFONO_DESTROY_CONTROLLER',
                'descripcion' => "{$nombre} ha sido eliminado",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar eliminar a {$nombre}",
                'codigo' => 'TELEFONO_DESTROY_CONTROLLER'
            ],
        ];

        return $BaseController->destroy($telefono, $mensaje);
    }

    /**
     * Restaurar el usuario que ha sido eliminado
     *
     * @param  \App\Mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function restore(Telefono $telefono)
    {
        $nombre  = $telefono->nombre_contacto;
        $BaseController  = new BaseController();

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'TELEFONO_RESTORE_CONTROLLER',
                'descripcion' => "{$nombre} ha sido dado de alta",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar dar de alta {$nombre}",
                'codigo' => 'TELEFONO_RESTORE_CONTROLLER'
            ],
        ];

        return $BaseController->restore($telefono, $mensaje);
    }
}
