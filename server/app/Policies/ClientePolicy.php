<?php

namespace App\Policies;

use App\Cliente;
use App\ClienteUsuario;
use App\Policies\UsuarioTienePermiso;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientePolicy
{
    use HandlesAuthorization;
    use UsuarioTienePermiso;

    /**
     * Determine whether the user can view the clientes.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @return mixed
     */
    public function index(User $usuario)
    {
        if ($usuario->esCliente()) {
            return false;
        }

        if ($usuario->esEmpleado()) {
            return $this->tienePermiso($usuario, 'ver clientes');
        }
    }

    /**
     * Determine whether the user can view the cliente.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Cliente  $cliente
     * @return mixed
     */
    public function view(User $usuario, Cliente $cliente)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacion($usuario, $cliente);
        }

        if ($usuario->esEmpleado()) {
            return $this->tienePermiso($usuario, 'ver clientes');
        }
    }

    /**
     * Determine whether the user can create clientes.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @return mixed
     */
    public function create(User $usuario)
    {
        if ($usuario->esCliente()) {
            return false;
        }

        if ($usuario->esEmpleado()) {
            return $this->tienePermiso($usuario, 'crear clientes');
        }
    }

    /**
     * Determine whether the user can update the cliente.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Cliente  $cliente
     * @return mixed
     */
    public function update(User $usuario, Cliente $cliente)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacion($usuario, $cliente);
        }

        if ($usuario->esEmpleado()) {
            return $this->tienePermiso($usuario, 'actualizar clientes');
        }
    }

    /**
     * Determine whether the user can delete the cliente.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Cliente  $cliente
     * @return mixed
     */
    public function delete(User $usuario, Cliente $cliente)
    {
        return $this->tienePermisoParaEliminar($usuario, $cliente);
    }

    /**
     * Determine whether the user can restore the cliente.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Cliente  $cliente
     * @return mixed
     */
    public function restore(User $usuario, Cliente $cliente)
    {
        return $this->tienePermisoParaEliminar($usuario, $cliente);
    }

    /**
     * Determine whether the user can permanently delete the cliente.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Cliente  $cliente
     * @return mixed
     */
    public function forceDelete(User $usuario, Cliente $cliente)
    {
        //
    }

    private function hayRelacion(ClienteUsuario $usuario, Cliente $cliente)
    {
        $hayRelacion = $usuario->id_cliente === $cliente->id;

        if (!$hayRelacion) {
            $this->deny('No tienes ninguna relación con el cliente');
        }

        return $hayRelacion;
    }

    private function tienePermisoParaEliminar(User $usuario, Cliente $cliente)
    {
        if ($usuario->esCliente()) {
            return false;
        }

        if ($usuario->esEmpleado()) {
            return $this->tienePermiso($usuario, 'eliminar clientes');
        }
    }
}
