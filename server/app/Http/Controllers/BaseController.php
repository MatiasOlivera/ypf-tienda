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
     * Muestra una lista de instancias
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
     * Guarda una instancia en la BD.
     *
     * @param array $parametros
     * @param array $nombres
     * @return JsonResponse
     */
    public function store(array $parametros, array $nombres): JsonResponse
    {
        try {
            $nombreModelo = "App\\{$parametros['modelo']}";
            $instancia = new $nombreModelo;
            $instancia->fill($parametros['input']);

            if ($instancia->save()) {
                $mensajeExito = new MensajeExito();
                $mensajeExito->guardar($nombres['exito']);

                return Respuesta::exito([$this->modeloSingular => $instancia], $mensajeExito, 201);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->guardar($nombres['error']);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Muestra una instancia específica
     *
     * @param Model $instancia
     * @return JsonResponse
     */
    public function show($instancia): JsonResponse
    {
        return Respuesta::exito([$this->modeloSingular => $instancia], null, 200);
    }

    /**
     * Actualizar la instancia específica en la BD.
     *
     * @param array $parametros
     * @param array $nombres
     * @return JsonResponse
     */
    public function update(array $parametros, array $nombres): JsonResponse
    {
        try {
            $instancia = $parametros['instancia'];
            $instancia->fill($parametros['input']);
            if ($instancia->save()) {
                $mensajeExito = new MensajeExito();
                $mensajeExito->actualizar($nombres['exito']);

                return Respuesta::exito([$this->modeloSingular => $instancia], $mensajeExito, 200);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->actualizar($nombres['error']);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Elimina la instancia específica de la BD
     *
     * @param Model $instancia
     * @param array $nombres
     * @return JsonResponse
     */
    public function destroy($instancia, array $nombres): JsonResponse
    {
        try {
            $eliminado = $instancia->delete();

            if ($eliminado) {
                $mensajeExito = new MensajeExito();
                $mensajeExito->eliminar($nombres['exito']);

                return Respuesta::exito([$this->modeloSingular => $instancia], $mensajeExito, 200);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->eliminar($nombres['error']);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     *  Restaurar la instancia que ha sido eliminada
     *
     * @param Model $instancia
     * @param array $nombres
     * @return JsonResponse
     */
    public function restore($instancia, array $nombres): JsonResponse
    {
        try {
            $restaurada = $instancia->restore();

            if ($restaurada) {
                $mensajeExito = new MensajeExito();
                $mensajeExito->restaurar($nombres['exito']);

                return Respuesta::exito([$this->modeloSingular => $instancia], $mensajeExito, 200);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->restaurar($nombres['error']);

            return Respuesta::error($mensajeError, 500);
        }
    }
}
