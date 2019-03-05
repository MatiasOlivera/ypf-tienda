<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
    public function index($consulta, $modelo, $paginacion, $mensajes)
    {
        try {
            $campoOrden = $paginacion->getCampoOrden();
            $paginado   = $paginacion->getRegistrosPorPagina();
            $relaciones = $consulta->getRelaciones();
            $eliminados = $consulta->getEliminados();
            $campos     = $consulta->getCampos();
            $buscar     = $consulta->getValorBuscar();

            if (!is_null($relaciones)) {
                $lista = $modelo::with($relaciones);
            } else {
                $lista = $modelo;
            }

            if ($eliminados) {
                $lista = $lista->onlyTrashed();
            }

            if (!is_null($campos)) {
                $lista = $lista->select($campos);
            }

            if (!is_null($buscar)) {
                $lista = $this->buscar($lista, $campos, $buscar);
            }

            if ($campoOrden) {
                $orden = $paginacion->getOrden();
                $lista = $lista->orderBy($campoOrden, $orden);
            }

            $Resultado = $lista->paginate($paginado);

            if ($Resultado) {
                $status = 200;
                return response()->json($Resultado, $status);
            }
        } catch (\Throwable $th) {
            $status = 500;
            $error  = $mensaje->getMensajeError();
            return response()->json($error, $status);
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
    public function store($inputs, $modelo, $mensaje)
    {
        try {
            $modelo->fill($inputs);
            if ($modelo->save()) {
                $status   = 201;
                $mensajes =  $mensaje->getMensajeExito();
                return response()->json($mensajes, $status);
            }
        } catch (\Throwable $th) {
            $status = 500;
            $error  = $mensaje->getMensajeError();
            return response()->json($error, $status);
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
    public function update($inputs, $modelo, $mensaje)
    {
        try {
            $modelo->fill($inputs);
            if ($modelo->save()) {
                $status                  =  200;
                $mensajes['codigo']      =  $mensaje->exitoCodigo;
                $mensajes['descripcion'] =  $mensaje->exitoDescripcion;

                return response()->json($mensajes, $status);
            }
        } catch (\Throwable $th) {
            $status               =  500;
            $error['codigo']      =  $mensaje->errorCodigo;
            $error['descripcion'] =  $mensaje->errorDescripcion;

            return response()->json($error, $status);
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
                $status   = 200;
                $mensajes = $mensaje->getMensajeExito();

                return response()->json($mensajes, $status);
            }
        } catch (\Throwable $th) {
            $status = 500;
            $error  = $mensaje->getMensajeError();

            return response()->json($error, $status);
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
                $status    = 200;
                $mensajes  = $mensaje->getMensajeExito();

                return response()->json($mensajes, $status);
            }
        } catch (\Throwable $th) {
            $status = 500;
            $error  = $mensaje->getMensajeError();

            return response()->json($error, $status);
        }
    }

    private function buscar($consulta, $campos, $buscar)
    {
        return $consulta->where(function ($query) use ($campos, $buscar) {
            foreach ($campos as $campo) {
                $query->orWhere($campo, 'like', "%{$buscar}%");
            }
        });
    }
}
