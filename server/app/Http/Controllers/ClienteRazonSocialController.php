<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\ClienteRazonSocial;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Carbon;

class ClienteRazonSocialController extends Controller
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
                'codigo'      => 'RAZON_INDEX_CONTROLLER',
            ],
        ];
        try {
            $razones = $cliente->razonesSociales;
            return response()->json(['datos' => $razones,], 200);
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
        $nombre  = $request->input('denominacion');
        //mensajes
        $mensajes = [
            'exito' => [
                'codigo'        => 'RAZON_STORE_CONTROLLER',
                'descripcion'   => " {$nombre} se ha creado con exito",
            ],
            'error' => [
                'descripcion'   => "Hubo un error al intentar guardar {$nombre}",
                'codigo'        => 'CATCH_RAZON_STORE'
            ],
        ];

        try {
            $inputs = [
                'nombre'    => $request->input('denominacion'),
                'cuit'      => $request->input('CUIT'),
                'id_loc'    => $request->input('localidad_id'),
                'calle'     => $request->input('calle'),
                'altura'    => $request->input('altura'),
                'area_tel'  => $request->input('area'),
                'tel'       => $request->input('telefono'),
                'mail'      => $request->input('mail'),
            ];
            $cliente->razonesSociales()->create($inputs);
            $razones = $cliente->razonesSociales;

            return response()->json([
                'datos'     => $razones,
                'mensaje'   => $mensajes['exito'],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($mensajes['error'], '400');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function show(ClienteRazonSocial $razonSocial)
    {
        $razonSocial->localidad;
        return $razonSocial;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente, ClienteRazonSocial $razonSocial)
    {
        //mensajes
        $nombre  = $request->input('denominacion');
        $mensajes = [
            'exito' => [
                'codigo' => 'RAZON_UPDATE_CONTROLLER',
                'descripcion' => " {$nombre} se ha modificado con exito",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar modificado a {$nombre}",
                'codigo' => 'CATCH_RAZON_UPDATE'
            ],
        ];
        try {
            $inputs = [
                'nombre'    => $request->input('denominacion'),
                'cuit'      => $request->input('CUIT'),
                'id_loc'    => $request->input('localidad_id'),
                'calle'     => $request->input('calle'),
                'altura'    => $request->input('altura'),
                'area_tel'  => $request->input('area'),
                'tel'       => $request->input('telefono'),
                'mail'      => $request->input('mail'),
            ];
            $razonSocial->fill($inputs);
            $save = $razonSocial->save();

            if ($save) {
                $razones = $cliente->razonesSociales;
                return response()->json([
                    'datos'     => $razones,
                    'mensaje'   => $mensajes['exito'],
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json($mensajes['error'], 400);
        }
    }

    /**
     * Restaurar el usuario que ha sido eliminado
     *
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function restore(ClienteRazonSocial $razonSocial)
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

    /**
     * Crear relacion estre cliente y razon
     * @param  App\Cliente $cliente
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function asociar(Cliente $cliente, ClienteRazonSocial $razonSocial)
    {
        $razon  = $razonSocial->nombre;
        $nombre = $cliente->cliente;

        //mensajes
        $mensajes = [
            'exito' => [
                'codigo' => 'RAZON_ASOCIAR_CONTROLLER',
                'descripcion' => "{$razon} se ha asociado a {$nombre}",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar asociar {$razon} a {$nombre}",
                'codigo' => 'CATCH_RAZON_ASOCIAR'
            ],
        ];

        try {
            $razonId = $razonSocial->id_razon;
            $cliente->razonesSociales()->attach($razonId);
            $razones = $cliente->razonesSociales;

            return response()->json([
                'datos'     => $razones,
                'mensaje'   => $mensajes['exito'],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($mensajes['error'], 400);
        }
    }

    /**
     * desasociar la relacion entre el cliente y la razon
     * @param  App\Cliente $cliente
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function desasociar(Cliente $cliente, ClienteRazonSocial $razonSocial)
    {
        $razon  = $razonSocial->nombre;
        $nombre = $cliente->cliente;

        //mensajes
        $mensajes = [
            'exito' => [
                'codigo' => 'RAZON_DESASOCIAR_CONTROLLER',
                'descripcion' => "{$razon} ha sido desasociado de {$nombre}",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar desasociar {$razon} de {$nombre}",
                'codigo' => 'CATCH_RAZON_DESASOCIAR'
            ],
        ];

        try {
            $razonId = $razonSocial->id_razon;
            $cliente->razonesSociales()->detach($razonId);
            $razones = $cliente->razonesSociales;

            return response()->json([
                'datos'     => $razones,
                'mensaje'   => $mensajes['exito'],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($mensajes['error'], 400);
        }
    }
}
