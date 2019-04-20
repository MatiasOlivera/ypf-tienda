<?php

namespace App\Auxiliares;

use Exception;
use App\Auxiliares\Mensaje;
use Illuminate\Http\JsonResponse;

final class Respuesta
{
    public static function exito(array $datos, ?MensajeExito $mensaje = null, int $codigoEstado = 200): JsonResponse
    {
        if ($codigoEstado > 208) {
            throw new Exception("El código de estado no se encuentra dentro del rango de respuestas correctas");
        }

        return Respuesta::getRespuesta($datos, $mensaje, $codigoEstado);
    }

    public static function error(MensajeError $mensaje, int $codigoEstado = 500): JsonResponse
    {
        if ($codigoEstado < 400) {
            throw new Exception("El código de estado no se encuentra dentro del rango de respuestas con errores");
        }

        return Respuesta::getRespuesta([], $mensaje, $codigoEstado);
    }

    private static function getRespuesta(array $datos, ?Mensaje $mensaje, $codigoEstado)
    {
        $objetoMensaje = $mensaje ? [ 'mensaje' => $mensaje->getObjeto() ] : [];
        $respuesta = array_merge($datos, $objetoMensaje);

        return response()->json($respuesta, $codigoEstado);
    }
}
