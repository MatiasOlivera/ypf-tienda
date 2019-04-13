<?php

namespace App\Http\Controllers;

use App\Provincia;
use Illuminate\Http\Request;
use App\controller\BaseController;

class ProvinciaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mensaje = [
            'error' => [
                'descripcion'   => 'Hemos tenido un error durante la consulta de datos, intente nuevamente',
                'codigo'        => 'CATCH_PROVINCIA_INDEX',
            ],
        ];
        try {
            $provincias = Provincias::all();
            return response()->json(['datos' => $provincias,], 200);
        } catch (\Throwable $th) {
            $respuesta = [
                'datos'     => null,
                'mensajes'  => [
                    'tipo'      => 'error',
                    'codigo'    => $mensaje['error']['codigo'],
                    'mensaje'   => $mensaje['error']['descripcion'],
                ],
            ];
            return response()->json($respuesta, 400);
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
        //mensajes
        $provincia   = $request->input('provincia');
        $mensaje = [
            'exito' => [
                'codigo' => 'PROVINCIA_STORE_CONTROLLER',
                'descripcion' => "{$provincia} se ha creado con exito",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar guardar {$provincia}",
                'codigo' => 'PROVINCIA_CATCH_STORE_CONTROLLER'
            ],
        ];

        $inputs   = [
            'nom_provincia' => $provincia,
        ];

        $parametros = [
            'inputs' => $inputs,
            'modelo' => 'Provincia',
        ];

        $BaseController   = new BaseController();
        return $BaseController->store($parametros, $mensaje);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function show(Provincia $provincia)
    {
        return $provincia;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Provincia $provincia)
    {
        $nombreProvincia   = $request->input('provincia');
        $inputs   = [
            'nom_provincia' => $nombreProvincia,
        ];

        //parametros
        $parametros = [
            'inputs' => $inputs,
            'modelo' => $provincia,
        ];

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'PROVINCIA_UPDATE_CONTROLLER',
                'descripcion' => "{$nombreProvincia} se ha modificado",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar modificar el cliente {$nombreProvincia}",
                'codigo' => 'CATCH_PROVINCIA_UPDATE_CONTROLLER'
            ],
        ];

        $metodo  = new BaseController();
        return $metodo->update($parametros, $mensaje);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provincia $provincia)
    {
        //mensajes
        $nombreProvincia  = $provincia->nom_provincia;
        $mensaje = [
            'exito' => [
                'codigo' => 'CLIENTE_DESTROY_CONTROLLER',
                'descripcion' => "{$nombreProvincia} ha sido eliminado",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar eliminar a {$nombreProvincia}",
                'codigo' => 'CLIENTE_DESTROY_CONTROLLER'
            ],
        ];

        $BaseController  = new BaseController();
        return $BaseController->destroy($provincia, $mensaje);
    }

    /**
     * Restaurar la provincia que ha sido eliminada
     *
     *@param  \App\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function restore(Provincia $provincia)
    {
        //mensajes
        $nombreProvincia  = $provincia->nom_provincia;
        $mensaje = [
            'exito' => [
                'codigo' => 'PROVINCIA_RESTORE_CONTROLLER',
                'descripcion' => "{$nombreProvincia} ha sido dado de alta",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar dar de alta a {$nombreProvincia}",
                'codigo' => 'CATCH_PROVINCIA_RESTORE'
            ],
        ];

        $BaseController  = new BaseController();
        return $BaseController->restore($provincia, $mensaje);
    }
}
