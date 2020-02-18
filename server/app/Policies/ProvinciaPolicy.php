<?php

namespace App\Policies;

use App\Provincia;
use App\Policies\UsuarioTienePermiso;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProvinciaPolicy
{
    use UsuarioTienePermiso;
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the provincias.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @return mixed
     */
    public function list(User $usuario)
    {
        if ($usuario->esCliente()) {
            return true;
        } else {
            return $this->tienePermiso($usuario, 'ver provincias');
        }
    }

    /**
     * Determine whether the user can view the provincia.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Provincia  $provincia
     * @return mixed
     */
    public function view(User $usuario, Provincia $provincia)
    {
        if ($usuario->esCliente()) {
            return true;
        } else {
            return $this->tienePermiso($usuario, 'ver provincias');
        }
    }

    /**
     * Determine whether the user can create provincias.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @return mixed
     */
    public function create(User $usuario)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'crear provincias');
        }
    }

    /**
     * Determine whether the user can update the provincia.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Provincia  $provincia
     * @return mixed
     */
    public function update(User $usuario, Provincia $provincia)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'actualizar provincias');
        }
    }

    /**
     * Determine whether the user can delete the provincia.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Provincia  $provincia
     * @return mixed
     */
    public function delete(User $usuario, Provincia $provincia)
    {
        return $this->tienePermisoParaEliminar($usuario, $provincia);
    }

    /**
     * Determine whether the user can restore the provincia.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Provincia  $provincia
     * @return mixed
     */
    public function restore(User $usuario, Provincia $provincia)
    {
        return $this->tienePermisoParaEliminar($usuario, $provincia);
    }

    private function tienePermisoParaEliminar(User $usuario, Provincia $provincia)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'eliminar provincias');
        }
    }
}
