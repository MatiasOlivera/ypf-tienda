<?php

namespace Tests\Feature\Utilidades\Api;

trait ProductoApi
{
    protected function obtenerProductos()
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', 'api/productos');
    }

    protected function obtenerProducto(int $productoID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/productos/$productoID");
    }

    protected function crearProducto(array $producto = [])
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', 'api/productos', $producto);
    }

    protected function actualizarProducto(int $productoID, array $producto = [])
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/productos/$productoID", $producto);
    }

    protected function eliminarProducto(int $productoID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/productos/$productoID");
    }

    protected function restaurarProducto(int $productoID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/productos/$productoID/restaurar");
    }
}
