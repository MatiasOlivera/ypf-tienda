<?php

namespace App\Http\Controllers;

use App\ClienteDomicilio;
use App\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\Cliente\Domicilio\ClienteDomicilioRequest;
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
        $mensaje = [
            'error' => [
                'descripcion'   => 'Hemos tenido un error durante la consulta de datos, intente nuevamente',
                'codigo'        => 'CATCH_DOMICILIO_INDEX',
            ],
        ];
        try {
            $domicilios = $cliente->domicilios;
            return response()->json(['datos' => $domicilios,], 200);
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
     * @param App\Cliente $cliente
     * @param  App\Http\Requests\Cliente\Domicilio\ClienteDomicilioRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClienteDomicilioRequest $request, Cliente $cliente)
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
            $inputs = $request->input('localidad_id', 'calle', 'numero', 'aclaracion');

            $domicilio = new ClienteDomicilio($inputs);
            $cliente->domicilios()->save($domicilio);
            $domicilio->localidad;
            $respuesta = [
                'datos'     => $domicilio,
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
     * @param  App\Cliente $cliente
     * @param  App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function show(ClienteDomicilio $domicilio)
    {
        $domicilio->localidad;
        return $domicilio;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Cliente\Domicilio\ClienteDomicilioRequest $request
     * @param  App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteDomicilioRequest $request, ClienteDomicilio $domicilio)
    {
        //mensajes
        $mensaje = [
            'exito' => [
                'codigo'        => 'DOMICILIO_STORE_CONTROLLER',
                'descripcion'   => "El domicilio se ha modificado con exito",
            ],
            'error' => [
                'descripcion'   => "Hubo un error al intentar modificar el domicilio",
                'codigo'        => 'CATCH_DOMICILIO_STORE',
            ],
        ];

        try {
            $inputs = $request->input('localidad_id', 'calle', 'numero', 'aclaracion');

            $domicilio->fill($inputs);
            $domicilio->save();
            $domicilio->localidad;
            $respuesta = [
                'datos'     => $domicilio,
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
     * @param  App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClienteDomicilio $domicilio)
    {

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

        $BaseController  = new BaseController();
        return $BaseController->destroy($domicilio, $mensaje);
    }

    /**
     * Restaurar el domicilio que ha sido eliminado
     *
     * @param  App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function restore(ClienteDomicilio $domicilio)
    {
        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'DOMICILIO_RESTORE_CONTROLLER',
                'descripcion' => "El domicilio ha sido dado de alta",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar dar de alta el domicilio",
                'codigo' => 'CATCH_DOMICILIO_RESTORE'
            ],
        ];

        $BaseController  = new BaseController();
        return $BaseController->restore($domicilio, $mensaje);
    }
}
