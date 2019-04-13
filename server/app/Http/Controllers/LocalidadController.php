<?php

namespace App\Http\Controllers;

use App\Localidad;
use App\Provincia;
use Illuminate\Http\Request;
use App\Http\Requests\LocalidadRequest;
use App\Http\controllers\BaseController;


class LocalidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Provincia $provincia)
    {
        //mensajes
        $mensaje = [
            'error' => [
                'descripcion' => 'Hemos tenido un error durante la consulta de datos, intente nuevamente',
                'codigo'      => 'LOCALIDAD_INDEX_CONTROLLER',
            ],
            'exito' => [
                'descripcion' => 'operacion exitosa',
                'codigo'      => 'LOCALIDAD_CATCH_INDEX_CONTROLLER',
            ]
        ];
        try {
            $localidades = $provincia->localidades;
            return response()->json(['datos' => $localidades,], 200);
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
    public function store(LocalidadRequest $request, Provincia $provincia)
    {
        $localidad = $request->input('localidad');
        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'LOCALIDAD_STORE_CONTROLLER',
                'descripcion' => "La localidad {$localidad} se ha creado con exito",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar guardar la localidad {$localidad}",
                'codigo' => 'CATCH_LOCALIDAD_STORE'
            ],
        ];
        $inputs = [
            'nom_localidad' => $localidad,
        ];

        try {
            $localidad = new Localidad($inputs);
            $provincia->localidades()->save($localidad);
            $respuesta = [
                'datos'     => $localidad,
                'mensajes'  => [
                    'tipo'      => 'exito',
                    'codigo'    => $mensaje['exito']['codigo'],
                    'mensaje'   => $mensaje['exito']['descripcion'],
                ],
            ];

            return response()->json($respuesta, 200);
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
    public function update(LocalidadRequest $request, Localidad $localidad)
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
     * Restaurar la localidad que ha sido eliminada
     *
     * @param  \App\Localidad  $localidad
     * @return \Illuminate\Http\Response
     */
    public function restore(Localidad $localidad)
    {
        //mensajes
        $nombre  = $localidad->nom_localidad;
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

        $BaseController  = new BaseController();
        return $BaseController->restore($localidad, $mensaje);
    }
}
