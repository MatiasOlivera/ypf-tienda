<?php

namespace Tests\Feature\Utilidades\Api;

trait LocalidadApi
{
    protected function obtenerLocalidades(int $provinciaID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/provincias/$provinciaID/localidades");
    }

    protected function obtenerLocalidad(int $localidadID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/localidades/$localidadID");
    }

    protected function crearLocalidad(array $localidad = [])
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', 'api/localidades', $localidad);
    }

    protected function actualizarLocalidad(int $localidadID, array $localidad = [])
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/localidades/$localidadID", $localidad);
    }

    protected function eliminarLocalidad(int $localidadID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/localidades/$localidadID");
    }

    protected function restaurarLocalidad(int $localidadID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/localidades/$localidadID/restaurar");
    }
}
