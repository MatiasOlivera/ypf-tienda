<?php

namespace App\Policies;

use App\Cliente;
use App\ClienteUsuario;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use App\Policies\UsuarioTienePermiso;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClienteUsuarioPolicy
{
    use HandlesAuthorization;
    use UsuarioTienePermiso;

    /**
     * Determine whether the user can view the usuarios de clientes
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @return mixed
     */
    public function list(User $usuario)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'ver usuarios de clientes');
        }
    }

    /**
     * Determine whether the user can view the cliente usuario.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteUsuario  $clienteUsuario
     * @return mixed
     */
    public function view(User $usuario, ClienteUsuario $clienteUsuario)
    {
        if ($usuario->esCliente()) {
            return $this->esElMismoUsuario($usuario, $clienteUsuario);
        } else {
            return $this->tienePermiso($usuario, 'ver usuarios de clientes');
        }
    }

    /**
     * Determine whether the user can create cliente usuarios.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @return mixed
     */
    public function create(User $usuario)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'crear usuarios de clientes');
        }
    }

    /**
     * Determine whether the user can update the cliente usuario.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteUsuario  $clienteUsuario
     * @return mixed
     */
    public function update(User $usuario, ClienteUsuario $clienteUsuario)
    {
        if ($usuario->esCliente()) {
            return $this->esElMismoUsuario($usuario, $clienteUsuario);
        } else {
            return $this->tienePermiso($usuario, 'actualizar usuarios de clientes');
        }
    }

    /**
     * Determine whether the user can delete the cliente usuario.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteUsuario  $clienteUsuario
     * @return mixed
     */
    public function delete(User $usuario, ClienteUsuario $clienteUsuario)
    {
        return $this->tienePermisoParaEliminar($usuario, $clienteUsuario);
    }

    /**
     * Determine whether the user can restore the cliente usuario.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteUsuario  $clienteUsuario
     * @return mixed
     */
    public function restore(User $usuario, ClienteUsuario $clienteUsuario)
    {
        return $this->tienePermisoParaEliminar($usuario, $clienteUsuario);
    }

    private function tienePermisoParaEliminar(User $usuario, ClienteUsuario $clienteUsuario)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'eliminar usuarios de clientes');
        }
    }

    private function esElMismoUsuario(User $usuario, ClienteUsuario $clienteUsuario)
    {
        $iguales = $usuario->id === $clienteUsuario->id;

        if (!$iguales) {
            $this->deny('Solamente puedes ver y modificar tus propios datos');
        }

        return $iguales;
    }
}
