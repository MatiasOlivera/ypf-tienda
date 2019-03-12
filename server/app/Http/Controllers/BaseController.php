<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Auxiliares\Consulta;

class BaseController
{
    /**
     * Muestra una lista de modelos.
     *
     * @param App\AuxiliaresApp\Auxiliares\Consulta  $consulta
     * @param Modelo $modelo
     * @param App\AuxiliaresApp\Auxiliares\Paginacion  $paginacion
     * @param App\AuxiliaresApp\Auxiliares\Mensajes  $mensajes
     * @return \Illuminate\Http\Response
     */
    public function index($parametros, $mensajes)
    {
        try {
            $consulta = new Consulta;
            $consulta->setParametros($parametros);
            $lista = $consulta->ejecutarconsulta();
            if ($lista) {
                return response()->json($lista, 200);
            }
        } catch (\Throwable $th) {
            return response()->json($mensajes['error'], 500);
        }
    }

    /**
     * Guarda un nuevo modelo en la BD.
     *
     * @param array  $inputs
     * @param Modelo $modelo
     * @param App\Auxiliares\Mensajes  $mensajes
     * @return \Illuminate\Http\Response
     */
    public function store($parametros, $mensaje)
    {
        try {
            $nombreModelo = "App\\{$parametros['modelo']}";
            $modelo = new $nombreModelo;
            $modelo->fill($parametros['inputs']);

            if ($modelo->save()) {
                return response()->json($mensaje['exito'], 201);
            }
        } catch (\Throwable $th) {
            return response()->json($mensaje['error'], 500);
        }
    }

    /**
     * Actualizar el modelo especifico en la BD.
     *
     * @param array  $inputs
     * @param Modelo $modelo
     * @param App\Auxiliares\Mensajes  $mensajes
     * @return \Illuminate\Http\Response
     */
    public function update($parametros, $mensaje)
    {
        try {
            $modelo = $parametros['modelo'];
            $modelo->fill($parametros['inputs']);
            if ($modelo->save()) {
                return response()->json($mensaje['exito'], 200);
            }
        } catch (\Throwable $th) {
            return response()->json($mensaje['error'], 500);
        }
    }

    /**
     * elimina el modelo especifico de la BD
     *
     * @param Modelo $modelo
     * @param App\Auxiliares\Mensajes  $mensajes
     * @return \Illuminate\Http\Response
     */
    public function destroy($modelo, $mensaje)
    {
        try {
            $eliminado = $modelo->delete();

            if ($eliminado) {
                return response()->json($mensaje['exito'], 200);
            }
        } catch (\Throwable $th) {
            return response()->json($mensaje['error'], 500);
        }
    }

    /**
     * Restaurar el modelo que ha sido eliminado
     *
     * @param Modelo $modelo
     * @param App\Auxiliares\Mensajes  $mensajes
     * @return \Illuminate\Http\Response
     */
    public function restore($modelo, $mensaje)
    {
        try {
            $restaurada = $modelo->restore();

            if ($restaurada) {
                return response()->json($mensaje['exito'], 200);
            }
        } catch (\Throwable $th) {
            return response()->json($mensaje['error'], 500);
        }
    }
}
