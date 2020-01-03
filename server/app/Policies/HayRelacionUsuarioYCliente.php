<?php

namespace App\Policies;

use App\Cliente;
use App\ClienteUsuario;

trait HayRelacionUsuarioYCliente
{
    private function hayRelacionEntreClienteUsuarioYCliente(ClienteUsuario $usuario, Cliente $cliente)
    {
        $hayRelacion = $usuario->id_cliente === $cliente->id;

        if (!$hayRelacion) {
            $this->deny('No tienes ninguna relación con el cliente');
        }

        return $hayRelacion;
    }
}
