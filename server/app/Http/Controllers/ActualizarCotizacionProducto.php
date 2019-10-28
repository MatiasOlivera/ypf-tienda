<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Cotizacion;
use App\CotizacionProducto;
use App\Auxiliares\Respuesta;
use App\Auxiliares\MensajeError;
use App\Auxiliares\MensajeExito;
use Illuminate\Http\JsonResponse;

trait ActualizarCotizacionProducto
{
    private function actualizarProductos(Cotizacion $cotizacion, array $productos): JsonResponse
    {
        $nombre = "Los productos";

        try {
            foreach ($productos as $inputProducto) {
                $id = $inputProducto['id'];
                $producto = Producto::withTrashed()
                    ->findOrFail($inputProducto['producto_id']);

                $atributos = [
                    'codigo' => $producto->codigo,
                    'cantidad' => $inputProducto['cantidad'],
                    'precio' => $inputProducto['precio']
                ];

                // Actualiza el producto existente
                if (!is_null($id)) {
                    $detalle = CotizacionProducto::findOrFail($id);
                    $detalle->fill($atributos);
                    $detalle->save();
                }

                // Guarda un nuevo producto
                if (is_null($id)) {
                    // Existe un producto con el mismo codigo y precio?
                    $detalleExistente = $cotizacion->productos()
                        ->where('codigo', $atributos['codigo'])
                        ->where('precio', $atributos['precio'])
                        ->first();

                    if ($detalleExistente) {
                        $detalleExistente->cantidad += $atributos['cantidad'];
                        $detalleExistente->save();
                    } else {
                        // Existe un producto con el mismo codigo pero que esta eliminado?
                        $detalleEliminado = $cotizacion->productos()
                            ->onlyTrashed()
                            ->where('codigo', $atributos['codigo'])
                            ->first();

                        if ($detalleEliminado) {
                            $detalleEliminado->restore();
                            $detalleEliminado->fill($atributos);
                            $detalleEliminado->save();
                        } else {
                            // No existe un producto en la cotizacion con el mismo codigo de producto ni precio
                            $cotizacion->productos()->create($atributos);
                            $cotizacion->save();
                        }
                    }
                }
            }

            $productosActualizados = Cotizacion::with('productos.producto')->findOrFail($cotizacion->id);
            $mensajeExito = new MensajeExito("$nombre han sido modificados", 'ACTUALIZADO');
            $respuesta = [$this->modeloPlural => $productosActualizados->productos];
            return Respuesta::exito($respuesta, $mensajeExito, 200);
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError(
                "$nombre no han sido actualizados debido a un error interno",
                'NO_ACTUALIZADO'
            );
            return Respuesta::error($mensajeError, 500);
        }
    }
}
