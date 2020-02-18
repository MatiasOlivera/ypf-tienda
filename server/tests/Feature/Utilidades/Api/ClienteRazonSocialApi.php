<?php

namespace Tests\Feature\Utilidades\Api;

trait ClienteRazonSocialApi
{
    protected function obtenerRazonesSociales(int $clienteID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/$clienteID/razones");
    }

    protected function obtenerRazonSocial(int $clienteID, int $razonSocialID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/$clienteID/razones/$razonSocialID");
    }

    protected function crearRazonSocial(int $clienteID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes/$clienteID/razones", []);
    }

    protected function actualizarRazonSocial(int $clienteID, int $razonSocialID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/clientes/$clienteID/razones/$razonSocialID", []);
    }

    protected function eliminarRazonSocial(int $clienteID, int $razonSocialID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/clientes/$clienteID/razones/$razonSocialID");
    }

    protected function restaurarRazonSocial(int $clienteID, int $razonSocialID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes/$clienteID/razones/$razonSocialID/restaurar");
    }

    protected function asociarClienteRazonSocial(array $cabeceras, int $clienteID, int $razonSocialID)
    {
        return $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/clientes/$clienteID/razones/$razonSocialID/asociar");
    }

    protected function desasociarClienteRazonSocial(array $cabeceras, int $clienteID, int $razonSocialID)
    {
        return $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/clientes/$clienteID/razones/$razonSocialID/desasociar");
    }
}
