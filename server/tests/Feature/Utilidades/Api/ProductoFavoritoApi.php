<?php

namespace Tests\Feature\Utilidades\Api;

trait ProductoFavoritoApi
{
    protected function agregarFavorito(int $productoID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/productos/$productoID/favorito");
    }

    protected function eliminarFavorito(int $productoID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/productos/$productoID/favorito");
    }
}
