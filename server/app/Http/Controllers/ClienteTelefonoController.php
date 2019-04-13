<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\ClienteTelefono;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Cliente\Telefono\ClienteTelefonoRequest;

class ClienteTelefonoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  App\Cliente $cliente
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Cliente $cliente)
    {
        $mensaje = [
            'error' => [
                'descripcion' => 'Hemos tenido un error durante la consulta de datos, intente nuevamente',
                'codigo'      => 'TELEFONO_INDEX_CONTROLLER',
            ],
        ];

        try {
            $telefonos = $cliente->telefonos;
            return response()->json(['ClienteTelefono' => $telefonos,], 200);
        } catch (\Throwable $th) {
            $respuesta = [
                'ClienteTelefono'     => null,
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
     * @param  App\Cliente $cliente
     * @param  App\Http\Requests\Cliente\Telefono\ClienteTelefonoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClienteTelefonoRequest $request, Cliente $cliente)
    {
        $inputs = $request->input('area', 'telefono', 'nombreContacto');

        $telefonoMensaje = ($inputs['nombreContacto'] === null) ? "{$inputs['area']} - {$inputs['telefono']}" : $inputs['nombreContacto'];

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'TELEFONO_STORE_CONTROLLER',
                'descripcion' => "El contacto {$telefonoMensaje} se ha creado con exito",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar guardar el N° de {$telefonoMensaje}",
                'codigo' => 'CATCH_TELEFONO_STORE'
            ],
        ];

        try {
            $telefono = new ClienteTelefono($inputs);
            $cliente->telefonos()->save($telefono);
            $respuesta = [
                'datos'     => $telefono,
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
     * @param  \App\ClienteTelefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function show(ClienteTelefono $telefono)
    {
        return $telefono;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Cliente\Telefono\ClienteTelefonoRequest  $request
     * @param  \App\ClienteTelefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteTelefonoRequest $request, ClienteTelefono $telefono)
    {
        $inputs = $request->input('area', 'telefono', 'nombreContacto');

        $telefonoMensaje = ($inputs['nombreContacto'] === null) ? "{$inputs['area']} - {$inputs['telefono']}" : $inputs['nombreContacto'];

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'TELEFONO_STORE_CONTROLLER',
                'descripcion' => "El contacto {$telefonoMensaje} se ha modificado",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar modificar el contacto {$telefonoMensaje}",
                'codigo' => 'CATCH_TELEFONO_UPDATE'
            ],
        ];

        try {
            $telefono->fill($inputs);
            $telefono->save();

            $respuesta = [
                'datos'     => $telefono,
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
     * Remove the specified resource from storage.
     *
     * @param  \App\ClienteTelefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClienteTelefono $telefono)
    {
        //mensajes
        $telefonoMensaje = ($telefono->nombreContacto === null) ? "{$telefono->area} - { $telefono->telefono}" : $telefono->nombreContacto;
        $mensaje = [
            'exito' => [
                'codigo' => 'TELEFONO_DESTROY_CONTROLLER',
                'descripcion' => "{$telefonoMensaje} ha sido eliminado",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar eliminar a {$telefonoMensaje}",
                'codigo' => 'CATCH_TELEFONO_DESTROY'
            ],
        ];

        $BaseController  = new BaseController();
        return $BaseController->destroy($telefono, $mensaje);
    }

    /**
     * Restaurar el Telefono que ha sido eliminado
     *
     * @param  \App\ClienteTelefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function restore(ClienteTelefono $telefono)
    {
        //mensajes
        $telefonoMensaje = ($telefono->nombreContacto === null) ? "{$telefono->area} - { $telefono->telefono}" : $telefono->nombreContacto;
        $mensaje = [
            'exito' => [
                'codigo' => 'TELEFONO_RESTORE_CONTROLLER',
                'descripcion' => "{$telefonoMensaje} ha sido dado de alta",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar dar de alta {$telefonoMensaje}",
                'codigo' => 'CATCH_TELEFONO_RESTORE'
            ],
        ];

        $BaseController  = new BaseController();
        return $BaseController->restore($telefono, $mensaje);
    }
}
