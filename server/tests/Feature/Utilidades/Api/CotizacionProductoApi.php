<?php

namespace Tests\Feature\Utilidades\Api;

trait CotizacionProductoApi
{
    protected function actualizarCotizacionProductos(int $cotizacionID, array $productos = [])
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/cotizaciones/$cotizacionID/productos", ['productos' => $productos]);
    }

    protected function eliminarCotizacionProducto(int $productoID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/cotizaciones/productos/$productoID");
    }
}
