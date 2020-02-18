<?php

namespace Tests\Feature\Utilidades\Api;

trait CategoriaProductoApi
{
    protected function obtenerCategorias()
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', 'api/categorias-productos');
    }

    protected function obtenerCategoria(int $categoriaID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/categorias-productos/$categoriaID");
    }

    protected function crearCategoria(array $categoria = [])
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', 'api/categorias-productos', $categoria);
    }

    protected function actualizarCategoria(int $categoriaID, array $categoria = [])
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/categorias-productos/$categoriaID", $categoria);
    }

    protected function eliminarCategoria(int $categoriaID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/categorias-productos/$categoriaID");
    }

    protected function restaurarCategoria(int $categoriaID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/categorias-productos/$categoriaID/restaurar");
    }
}
