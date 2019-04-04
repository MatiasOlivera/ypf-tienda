<?php

namespace App\Http\Controllers;

use App\Localidad;
use Illuminate\Http\Request;
use App\controller\BaseController;


class LocalidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mensajes = [
            'error' => [
                'descripcion' => 'Hemos tenido un error durante la consulta de datos, intente nuevamente',
                'codigo'      => 'LOCALIDAD_INDEX_CONTROLLER',
            ],
        ];
        try {
            $localidades = Localidad::all();
            return response()->json($localidades, 200);
        } catch (\Throwable $th) {
            return response()->json($mensajes['error'], '400');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nombre = $request->input('localidad');
        $parametros = [
            'inputs' => [
                'id_localidad'  => $request->input('id_localidad'),
                'nom_localidad' => $request->input('localidad'),
                'id_provincia'  => $request->input('provincia_id'),
            ],
            'modelo' => 'Localidad',
        ];

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'LOCALIDAD_STORE_CONTROLLER',
                'descripcion' => "La localidad {$nombre} se ha creado con exito",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar guardar la localidad {$nombre}",
                'codigo' => 'CATCH_LOCALIDAD_STORE'
            ],
        ];

        $BaseController = new BaseController();
        return $BaseController->store($parametros, $mensaje);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Localidad  $localidad
     * @return \Illuminate\Http\Response
     */
    public function show(Localidad $localidad)
    {
        return $localidad;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Localidad  $localidad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Localidad $localidad)
    {
        $nombre  = $request->input('localidad');
        $parametros = [
            'inputs' => [
                'nom_localidad' => $request->input('localidad'),
                'id_provincia'  => $request->input('provincia_id'),
            ],
            'modelo' => $localidad,
        ];


        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'LOCALIDAD_STORE_CONTROLLER',
                'descripcion' => "{$nombre} se ha modificado",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar modificar {$nombre}",
                'codigo' => 'CATCH_LOCALIDAD_UPDATE'
            ],
        ];

        $BaseController  = new BaseController();
        return $BaseController->update($parametros, $mensaje);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Localidad  $localidad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Localidad $localidad)
    {
        $nombre  = $localidad->nom_localidad;
        $BaseController  = new BaseController();

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'LOCALIDAD_DESTROY_CONTROLLER',
                'descripcion' => "{$nombre} ha sido eliminada",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar eliminar {$nombre}",
                'codigo' => 'CATCH_LOCALIDAD_DESTROY'
            ],
        ];

        return $BaseController->destroy($localidad, $mensaje);
    }

    /**
     * Restaurar el usuario que ha sido eliminado
     *
     * @param  \App\Localidad  $localidad
     * @return \Illuminate\Http\Response
     */
    public function restore(Telefono $localidad)
    {
        $nombre  = $localidad->nom_localidad;
        $BaseController  = new BaseController();

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'LOCALIDAD_RESTORE_CONTROLLER',
                'descripcion' => "{$nombre} ha sido dada de alta",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar dar de alta {$nombre}",
                'codigo' => 'CATCH_LOCALIDAD_RESTORE'
            ],
        ];

        return $BaseController->restore($localidad, $mensaje);
    }
}
