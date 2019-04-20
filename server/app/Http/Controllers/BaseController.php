<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Auxiliares\{Consulta,Respuesta,MensajeExito,MensajeError};

class BaseController
{
    private $modeloSingular;
    private $modeloPlural;

    public function __construct(string $modeloSingular, string $modeloPlural)
    {
        $this->modeloSingular = $modeloSingular;
        $this->modeloPlural = $modeloPlural;
    }

    /**
     * Muestra una lista de modelos.
     *
     * @param array $parametros
     * @param string $nombre
     * @return JsonResponse
     */
    public function index(array $parametros, string $nombre): JsonResponse
    {
        try {
            $consulta = new Consulta;
            $consulta->setParametros($parametros);
            $resultado = $consulta->ejecutarconsulta();

            $respuesta = [
                $this->modeloPlural => $resultado['datos'],
                'paginacion' => $resultado['paginacion']
            ];

            if ($resultado) {
                return Respuesta::exito($respuesta, null, 200);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->obtenerTodos($nombre);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Guarda un nuevo modelo en la BD.
     *
     * @param array $parametros
     * @param array $mensajes
     * @return JsonResponse
     */
    public function store(array $parametros, array $mensajes): JsonResponse
    {
        try {
            $nombreModelo = "App\\{$parametros['modelo']}";
            $modelo = new $nombreModelo;
            $modelo->fill($parametros['inputs']);

            if ($modelo->save()) {
                $mensajeExito = new MensajeExito();
                $mensajeExito->guardar($mensajes['exito']);

                return Respuesta::exito([$this->modeloSingular => $modelo], $mensajeExito, 201);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->guardar($mensajes['error']);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Muestra un modelo específico
     *
     * @param Model $modelo
     * @return JsonResponse
     */
    public function show($modelo): JsonResponse
    {
        return Respuesta::exito([$this->modeloSingular => $modelo], null, 200);
    }

    /**
     * Actualizar el modelo especifico en la BD.
     *
     * @param array $parametros
     * @param array $mensajes
     * @return JsonResponse
     */
    public function update(array $parametros, array $mensajes): JsonResponse
    {
        try {
            $modelo = $parametros['modelo'];
            $modelo->fill($parametros['inputs']);
            if ($modelo->save()) {
                $mensajeExito = new MensajeExito();
                $mensajeExito->actualizar($mensajes['exito']);

                return Respuesta::exito([$this->modeloSingular => $modelo], $mensajeExito, 200);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->actualizar($mensajes['error']);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Elimina el modelo especifico de la BD
     *
     * @param Model $modelo
     * @param array $mensajes
     * @return JsonResponse
     */
    public function destroy($modelo, array $mensajes): JsonResponse
    {
        try {
            $eliminado = $modelo->delete();

            if ($eliminado) {
                $mensajeExito = new MensajeExito();
                $mensajeExito->eliminar($mensajes['exito']);

                return Respuesta::exito([$this->modeloSingular => $modelo], $mensajeExito, 200);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->eliminar($mensajes['error']);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     *  Restaurar el modelo que ha sido eliminado
     *
     * @param Model $modelo
     * @param array $mensajes
     * @return JsonResponse
     */
    public function restore($modelo, array $mensajes): JsonResponse
    {
        try {
            $restaurada = $modelo->restore();

            if ($restaurada) {
                $mensajeExito = new MensajeExito();
                $mensajeExito->restaurar($mensajes['exito']);

                return Respuesta::exito([$this->modeloSingular => $modelo], $mensajeExito, 200);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->restaurar($mensajes['error']);

            return Respuesta::error($mensajeError, 500);
        }
    }
}
