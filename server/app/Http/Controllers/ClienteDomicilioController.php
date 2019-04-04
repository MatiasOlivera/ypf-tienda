<?php

namespace App\Http\Controllers;

use App\ClienteDomicilio;
use App\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class ClienteDomicilioController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param App\Cliente $cliente
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Cliente $cliente)
    {
        $mensajes = [
            'error' => [
                'descripcion'   => 'Hemos tenido un error durante la consulta de datos, intente nuevamente',
                'codigo'        => 'DOMICILIO_INDEX_CONTROLLER',
            ],
        ];
        try {
            $domicilios = $cliente->domicilios;
            return response()->json(['datos' => $domicilios,], 200);
        } catch (\Throwable $th) {
            return response()->json($mensajes['error'], '400');
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param App\Cliente $cliente
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Cliente $cliente)
    {
        //mensajes
        $mensaje = [
            'exito' => [
                'codigo'        => 'DOMICILIO_STORE_CONTROLLER',
                'descripcion'   => "El domicilio se ha creado con exito",
            ],
            'error' => [
                'descripcion'   => "Hubo un error al intentar guardar el domicilio",
                'codigo'        => 'CATCH_DOMICILIO_STORE'
            ],
        ];

        try {
            $inputs = [
                'id_loc'            =>  $request->input('localidad_id'),
                'tel'               =>  $request->input('calle'),
                'nombre_contacto'   =>  $request->input('altura'),
                'acla'              =>  $request->input('aclaracion'),
            ];

            $domicilio = new ClienteDomicilio($inputs);
            $cliente->domicilios()->save($domicilio);
            $domicilios = $cliente->domicilios;

            return response()->json(
                [
                    'datos'     => $domicilios,
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
     * @param App\Cliente $cliente
     * @param  \App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function show(ClienteDomicilio $domicilio)
    {
        return $domicilio;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente, ClienteDomicilio $domicilio)
    {
        //mensajes
        $mensaje = [
            'exito' => [
                'codigo'        => 'DOMICILIO_STORE_CONTROLLER',
                'descripcion'   => "El domicilio se ha modificado con exito",
            ],
            'error' => [
                'descripcion'   => "Hubo un error al intentar modificar el domicilio",
                'codigo'        => 'CATCH_DOMICILIO_STORE'
            ],
        ];

        try {
            $inputs = [
                'id_loc'            =>  $request->input('localidad_id'),
                'tel'               =>  $request->input('calle'),
                'nombre_contacto'   =>  $request->input('altura'),
                'acla'              =>  $request->input('aclaracion'),
            ];

            $domicilio->fill($inputs);
            $domicilio->save();
            $domicilios = $cliente->domicilios;

            return response()->json(
                [
                    'datos'     => $domicilios,
                    'mensajes'  => $mensaje['exito'],
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(['error' => $mensaje['error'],], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClienteDomicilio $domicilio)
    {
        $BaseController  = new BaseController();

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'DOMICILIO_DESTROY_CONTROLLER',
                'descripcion' => "El domicilio ha sido eliminado",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar eliminar el domicilio",
                'codigo' => 'CATCH_DOMICILIO_DESTROY'
            ],
        ];

        return $BaseController->destroy($domicilio, $mensaje);
    }

    /**
     * Restaurar el usuario que ha sido eliminado
     *
     * @param  \App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function restore(ClienteDomicilio $domicilio)
    {
        $BaseController  = new BaseController();

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'TELEFONO_RESTORE_CONTROLLER',
                'descripcion' => "El domicilio ha sido dado de alta",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar dar de alta el domicilio",
                'codigo' => 'CATCH_TELEFONO_RESTORE'
            ],
        ];

        return $BaseController->restore($domicilio, $mensaje);
    }
}
