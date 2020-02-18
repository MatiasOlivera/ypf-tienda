<?php

namespace Tests\Feature\Utilidades\Api;

trait ProvinciaApi
{
    protected function obtenerProvincias()
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', 'api/provincias');
    }

    protected function obtenerProvincia(int $provinciaID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/provincias/$provinciaID");
    }

    protected function crearProvincia(array $provincia = [])
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', 'api/provincias', $provincia);
    }

    protected function actualizarProvincia(int $provinciaID, array $provincia = [])
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/provincias/$provinciaID", $provincia);
    }

    protected function eliminarProvincia(int $provinciaID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/provincias/$provinciaID");
    }

    protected function restaurarProvincia(int $provinciaID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/provincias/$provinciaID/restaurar");
    }
}
