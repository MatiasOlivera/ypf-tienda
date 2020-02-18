<?php

namespace Tests\Feature\Utilidades\Api;

trait ClienteTelefonoApi
{
    protected function obtenerTelefonos(int $clienteID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/$clienteID/telefonos");
    }

    protected function obtenerTelefono(int $clienteID, int $telefonoID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/$clienteID/telefonos/$telefonoID");
    }

    protected function crearTelefono(int $clienteID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes/$clienteID/telefonos", []);
    }

    protected function actualizarTelefono(int $clienteID, int $telefonoID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/clientes/$clienteID/telefonos/$telefonoID", []);
    }

    protected function eliminarTelefono(int $clienteID, int $telefonoID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/clientes/$clienteID/telefonos/$telefonoID");
    }

    protected function restaurarTelefono(int $clienteID, int $telefonoID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes/$clienteID/telefonos/$telefonoID/restaurar");
    }
}
