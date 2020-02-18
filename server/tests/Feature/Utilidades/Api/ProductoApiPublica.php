<?php

namespace Tests\Feature\Utilidades\Api;

trait ProductoApiPublica
{
    protected function obtenerProductos()
    {
        return $this->json('GET', 'api/publica/productos');
    }

    protected function obtenerProducto(int $productoID)
    {
        return $this->json('GET', "api/publica/productos/$productoID");
    }
}
