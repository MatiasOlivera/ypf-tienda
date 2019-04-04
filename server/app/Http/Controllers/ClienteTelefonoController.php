<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\ClienteTelefono;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

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
        $mensajes = [
            'error' => [
                'descripcion' => 'Hemos tenido un error durante la consulta de datos, intente nuevamente',
                'codigo'      => 'TELEFONO_INDEX_CONTROLLER',
            ],
        ];

        try {
            $telefonos = $cliente->telefonos;
            return response()->json($telefonos, 200);
        } catch (\Throwable $th) {
            return response()->json($mensajes['error'], '400');
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param  App\Cliente $cliente
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Cliente $cliente)
    {
        $inputs = [
            'area'             => $request->input('area'),
            'tel'              => $request->input('telefono'),
            'nombre_contacto'  => $request->input('nombreContacto'),
        ];

        $telefonoMensaje = ($inputs['nombre_contacto'] === null) ? "{$inputs['area']} - {$inputs['tel']}" : $inputs['nombre_contacto'];

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
            $telefonos = $cliente->telefonos;

            return response()->json(
                [
                    'datos'     => $telefonos,
                    'mensajes'  => $mensaje['exito'],
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(['error' => $mensaje['error'],], 400);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClienteTelefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente, ClienteTelefono $telefono)
    {
        $inputs = [
            'area'             => $request->input('area'),
            'tel'              => $request->input('telefono'),
            'nombre_contacto'  => $request->input('nombreContacto'),
        ];

        $telefonoMensaje = ($inputs['nombre_contacto'] === null) ? "{$inputs['area']} - {$inputs['tel']}" : $inputs['nombre_contacto'];

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
            $save = $telefono->save();
            if ($save) {
                $telefonos = $cliente->telefonos;

                return response()->json(
                    [
                        'datos'     => $telefonos,
                        'mensajes'  => $mensaje['exito'],
                    ],
                    200
                );
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $mensaje['error'],], 400);
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
        $telefonoMensaje = ($telefono->nombre_contacto === null) ? "{$telefono->area} - { $telefono->tel}" : $telefono->nombre_contacto;

        //mensajes
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
     * Restaurar el usuario que ha sido eliminado
     *
     * @param  \App\ClienteTelefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function restore(ClienteTelefono $telefono)
    {
        $telefonoMensaje = ($telefono->nombre_contacto === null) ? "{$telefono->area} - { $telefono->tel}" : $telefono->nombre_contacto;

        //mensajes
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
