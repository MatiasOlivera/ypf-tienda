<?php

namespace Tests\Feature\Utilidades\Api;

trait ClienteDomicilioApi
{
    protected function obtenerDomicilios(int $clienteID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/$clienteID/domicilios");
    }

    protected function obtenerDomicilio(int $clienteID, int $domicilioID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/$clienteID/domicilios/$domicilioID");
    }

    protected function crearDomicilio(int $clienteID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes/$clienteID/domicilios", []);
    }

    protected function actualizarDomicilio(int $clienteID, int $domicilioID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/clientes/$clienteID/domicilios/$domicilioID", []);
    }

    protected function eliminarDomicilio(int $clienteID, int $domicilioID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/clientes/$clienteID/domicilios/$domicilioID");
    }

    protected function restaurarDomicilio(int $clienteID, int $domicilioID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes/$clienteID/domicilios/$domicilioID/restaurar");
    }
}
