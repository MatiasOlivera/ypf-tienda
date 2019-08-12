<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Auxiliares\{Consulta,Respuesta,MensajeExito,MensajeError};

class BaseController
{
    private $modeloSingular;
    private $modeloPlural;
    private $generoModelo;

    public function __construct(string $modeloSingular, string $modeloPlural, string $generoModelo)
    {
        $this->modeloSingular = $modeloSingular;
        $this->modeloPlural = $modeloPlural;
        $this->generoModelo = $generoModelo;
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
            $resultado = $consulta->ejecutarconsulta($parametros);

            $respuesta = [
                $this->modeloPlural => $resultado->items(),
                'paginacion' => $this->getPaginacion($resultado)
            ];

            return Respuesta::exito($respuesta, null, 200);
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
     * @param string|array $nombre
     * @return JsonResponse
     */
    public function store(array $parametros, $nombre): JsonResponse
    {
        $nombres = $this->getNombres($nombre);

        try {
            $nombreModelo = "App\\{$parametros['modelo']}";
            $instancia = new $nombreModelo;
            $instancia->fill($parametros['inputs']);

            if ($instancia->save()) {
                $id = $instancia->getKey();
                $instanciaGuardada = $nombreModelo::findOrFail($id);

                $mensajeExito = new MensajeExito();
                $mensajeExito->guardar($nombres['exito'], $this->generoModelo);

                return Respuesta::exito([$this->modeloSingular => $instanciaGuardada], $mensajeExito, 201);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->guardar($nombres['error'], $this->generoModelo);

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
     * @param array $nombre
     * @return JsonResponse
     */
    public function update(array $parametros, array $nombre): JsonResponse
    {
        $nombres = $this->getNombres($nombre);

        try {
            $instancia = $parametros['instancia'];
            $instancia->fill($parametros['inputs']);
            if ($instancia->save()) {
                $mensajeExito = new MensajeExito();
                $mensajeExito->actualizar($nombres['exito'], $this->generoModelo);

                return Respuesta::exito([$this->modeloSingular => $instancia], $mensajeExito, 200);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->actualizar($nombres['error'], $this->generoModelo);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Elimina la instancia específica de la BD
     *
     * @param Model $instancia
     * @param string|array $nombre
     * @return JsonResponse
     */
    public function destroy($instancia, $nombre): JsonResponse
    {
        $nombres = $this->getNombres($nombre);

        try {
            $eliminado = $instancia->delete();

            if ($eliminado) {
                $mensajeExito = new MensajeExito();
                $mensajeExito->eliminar($nombres['exito'], $this->generoModelo);

                return Respuesta::exito([$this->modeloSingular => $instancia], $mensajeExito, 200);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->eliminar($nombres['error'], $this->generoModelo);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     *  Restaurar la instancia que ha sido eliminada
     *
     * @param Model $instancia
     * @param string|array $nombre
     * @return JsonResponse
     */
    public function restore($instancia, $nombre): JsonResponse
    {
        $nombres = $this->getNombres($nombre);

        try {
            $restaurada = $instancia->restore();

            if ($restaurada) {
                $mensajeExito = new MensajeExito();
                $mensajeExito->restaurar($nombres['exito'], $this->generoModelo);

                return Respuesta::exito([$this->modeloSingular => $instancia], $mensajeExito, 200);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->restaurar($nombres['error'], $this->generoModelo);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Obtener el objeto paginación
     *
     * @param $resultado
     * @return array
     */
    private function getPaginacion($resultado): array
    {
        return [
            "total" => $resultado->total(),
            "porPagina" => $resultado->perPage(),
            "paginaActual" => $resultado->currentPage(),
            "ultimaPagina" => $resultado->lastPage(),
            "desde" => $resultado->firstItem(),
            "hasta" => $resultado->lastItem(),
            "rutas" => [
                "primeraPagina" => $resultado->toArray()['first_page_url'],
                "ultimaPagina" => $resultado->toArray()['last_page_url'],
                "siguientePagina" => $resultado->nextPageUrl(),
                "paginaAnterior" => $resultado->previousPageUrl(),
                "base" => $resultado->resolveCurrentPath(),
            ]
        ];
    }

    /**
     * Genera un objeto nombres o devuelve el objeto original
     *
     * @param string|array $nombre El nombre del modelo o los nombres utilizados en los mensajes éxito y error
     * @return array Un objeto con los nombres
     */
    private function getNombres($nombre): array
    {
        if (is_string($nombre)) {
            $nombres['exito'] = $nombre;
            $nombres['error'] = $nombre;
            return $nombres;
        }

        if (is_array($nombre)) {
            if (!array_key_exists('exito', $nombre)) {
                throw new Exception("El argumento nombre debe tener la clave exito");
            }

            if (!array_key_exists('error', $nombre)) {
                throw new Exception("El argumento nombre debe tener la clave error");
            }

            return $nombre;
        }

        throw new Exception("El argumento nombre debe ser string o array");
    }
}
