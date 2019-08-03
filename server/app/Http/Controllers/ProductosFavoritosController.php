<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;
use App\Auxiliares\Respuesta;
use App\Auxiliares\MensajeError;
use App\Auxiliares\MensajeExito;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductoResource;

class ProductosFavoritosController extends Controller
{
    /**
     * Guardar el producto como favorito
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function asociar(Request $request, Producto $producto)
    {
        try {
            $usuario = Auth::user();
            $usuario->productosFavoritos()->attach($producto->id);

            $exito = "Se guardo el producto {$producto->nombre} como favorito";
            $mensajeExito = new MensajeExito($exito, 'ASOCIADOS');

            return (new ProductoResource($producto))
                ->additional(['mensaje' => $mensajeExito->toJson()]);
        } catch (\Throwable $th) {
            $error = "No se puedo guardar el producto {$producto->nombre} como favorito";
            $mensajeError = new MensajeError($error, 'NO_ASOCIADOS');
            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Quitar el producto de la lista de favoritos
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desasociar(Request $request, Producto $producto)
    {
        try {
            $usuario = Auth::user();
            $usuario->productosFavoritos()->detach($producto->id);

            $exito = "Se quito el producto {$producto->nombre} de la lista de favoritos";
            $mensajeExito = new MensajeExito($exito, 'DESASOCIADOS');

            return (new ProductoResource($producto))
                ->additional(['mensaje' => $mensajeExito->toJson()]);
        } catch (\Throwable $th) {
            $error = "No se puedo quitar el producto {$producto->nombre} de la lista de favoritos";
            $mensajeError = new MensajeError($error, 'NO_DESASOCIADOS');
            return Respuesta::error($mensajeError, 500);
        }
    }
}
