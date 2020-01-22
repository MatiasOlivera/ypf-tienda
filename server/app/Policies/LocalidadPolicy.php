<?php

namespace App\Policies;

use App\Localidad;
use App\Provincia;
use Illuminate\Foundation\Auth\User;
use App\Policies\UsuarioTienePermiso;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocalidadPolicy
{
    use UsuarioTienePermiso;
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the localidades.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Provincia  $provincia
     * @return mixed
     */
    public function list(User $usuario, Provincia $provincia)
    {
        if ($usuario->esCliente()) {
            return true;
        } else {
            return $this->tienePermiso($usuario, 'ver localidades');
        }
    }

    /**
     * Determine whether the user can view the localidad.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Localidad  $localidad
     * @return mixed
     */
    public function view(User $usuario, Localidad $localidad)
    {
        if ($usuario->esCliente()) {
            return true;
        } else {
            return $this->tienePermiso($usuario, 'ver localidades');
        }
    }

    /**
     * Determine whether the user can create localiades.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @return mixed
     */
    public function create(User $usuario)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'crear localidades');
        }
    }

    /**
     * Determine whether the user can update the localidad.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Localidad  $localidad
     * @return mixed
     */
    public function update(User $usuario, Localidad $localidad)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'actualizar localidades');
        }
    }

    /**
     * Determine whether the user can delete the localidad.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Localidad  $localidad
     * @return mixed
     */
    public function delete(User $usuario, Localidad $localidad)
    {
        return $this->tienePermisoParaEliminar($usuario, $localidad);
    }

    /**
     * Determine whether the user can restore the localidad.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Localidad  $localidad
     * @return mixed
     */
    public function restore(User $usuario, Localidad $localidad)
    {
        return $this->tienePermisoParaEliminar($usuario, $localidad);
    }

    private function tienePermisoParaEliminar(User $usuario, Localidad $localidad)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'eliminar localidades');
        }
    }
}
