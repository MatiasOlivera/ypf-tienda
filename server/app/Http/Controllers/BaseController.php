<?php

namespace App\Http\Controllers;

use App\Auxiliares\{Consulta,Respuesta,MensajeExito,MensajeError};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
     * @param App\AuxiliaresApp\Auxiliares\Consulta  $consulta
     * @param Modelo $modelo
     * @param App\AuxiliaresApp\Auxiliares\Paginacion  $paginacion
     * @param App\AuxiliaresApp\Auxiliares\Mensajes  $mensajes
     * @return \Illuminate\Http\Response
     */
    public function index($parametros, $nombre)
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
     * @param array  $inputs
     * @param Modelo $modelo
     * @param App\Auxiliares\Mensajes  $mensajes
     * @return \Illuminate\Http\Response
     */
    public function store($parametros, $mensajes)
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
     * @param Modelo $modelo
     * @return \Illuminate\Http\Response
     */
    public function show($modelo)
    {
        return Respuesta::exito([$this->modeloSingular => $modelo], null, 200);
    }

    /**
     * Actualizar el modelo especifico en la BD.
     *
     * @param array  $inputs
     * @param Modelo $modelo
     * @param App\Auxiliares\Mensajes  $mensajes
     * @return \Illuminate\Http\Response
     */
    public function update($parametros, $mensajes)
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
     * elimina el modelo especifico de la BD
     *
     * @param Modelo $modelo
     * @param App\Auxiliares\Mensajes  $mensajes
     * @return \Illuminate\Http\Response
     */
    public function destroy($modelo, $mensajes)
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
     * Restaurar el modelo que ha sido eliminado
     *
     * @param Modelo $modelo
     * @param App\Auxiliares\Mensajes  $mensajes
     * @return \Illuminate\Http\Response
     */
    public function restore($modelo, $mensajes)
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
