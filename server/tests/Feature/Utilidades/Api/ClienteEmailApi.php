<?php

namespace Tests\Feature\Utilidades\Api;

trait ClienteEmailApi
{
    protected function obtenerEmails(int $clienteID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/$clienteID/emails");
    }

    protected function obtenerEmail(int $clienteID, int $emailID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/$clienteID/emails/$emailID");
    }

    protected function crearEmail(int $clienteID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes/$clienteID/emails", []);
    }

    protected function actualizarEmail(int $clienteID, int $emailID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/clientes/$clienteID/emails/$emailID", []);
    }

    protected function eliminarEmail(int $clienteID, int $emailID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/clientes/$clienteID/emails/$emailID");
    }

    protected function restaurarEmail(int $clienteID, int $emailID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes/$clienteID/emails/$emailID/restaurar");
    }
}
