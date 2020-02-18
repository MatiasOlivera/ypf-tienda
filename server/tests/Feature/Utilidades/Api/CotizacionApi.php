<?php

namespace Tests\Feature\Utilidades\Api;

trait CotizacionApi
{
    protected function obtenerCotizaciones()
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', 'api/cotizaciones');
    }

    protected function obtenerCotizacionesDelCliente(int $clienteID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/{$clienteID}/cotizaciones");
    }

    protected function obtenerCotizacion(int $cotizacionID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/cotizaciones/$cotizacionID");
    }

    protected function crearCotizacionDelCliente(int $clienteID, array $cotizacion = [])
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes/{$clienteID}/cotizaciones", $cotizacion);
    }

    protected function actualizarCotizacion(int $cotizacionID, array $cotizacion = [])
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/clientes/cotizaciones/$cotizacionID", $cotizacion);
    }

    protected function eliminarCotizacion(int $cotizacionID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/clientes/cotizaciones/$cotizacionID");
    }
}
