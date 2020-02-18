<?php

namespace Tests\Feature\Utilidades\Api;

trait ClienteUsuarioApi
{
    protected function obtenerClienteUsuarios()
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/usuarios");
    }

    protected function obtenerClienteUsuario(int $usuarioID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('GET', "api/clientes/usuarios/$usuarioID");
    }

    protected function crearClienteUsuario(array $usuario = [])
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes/usuarios", $usuario);
    }

    protected function actualizarClienteUsuario(int $usuarioID, array $usuario = [])
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('PUT', "api/clientes/usuarios/$usuarioID", $usuario);
    }

    protected function eliminarClienteUsuario(int $usuarioID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('DELETE', "api/clientes/usuarios/$usuarioID");
    }

    protected function restaurarClienteUsuario(int $usuarioID)
    {
        return $this
            ->withHeaders($this->cabeceras)
            ->json('POST', "api/clientes/usuarios/$usuarioID/restaurar");
    }
}
