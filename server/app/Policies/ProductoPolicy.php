<?php

namespace App\Policies;

use App\Producto;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use App\Policies\UsuarioTienePermiso;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductoPolicy
{
    use UsuarioTienePermiso;
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the productos.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @return mixed
     */
    public function list(?User $usuario)
    {
        return true;
    }

    /**
     * Determine whether the user can view the producto.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Producto  $producto
     * @return mixed
     */
    public function view(?User $usuario, Producto $producto)
    {
        return true;
    }

    /**
     * Determine whether the user can create productos.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @return mixed
     */
    public function create(User $usuario)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'crear productos');
        }
    }

    /**
     * Determine whether the user can update the producto.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Producto  $producto
     * @return mixed
     */
    public function update(User $usuario, Producto $producto)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'actualizar productos');
        }
    }

    /**
     * Determine whether the user can delete the producto.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Producto  $producto
     * @return mixed
     */
    public function delete(User $usuario, Producto $producto)
    {
        return $this->tienePermisoParaEliminar($usuario, $producto);
    }

    /**
     * Determine whether the user can restore the producto.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Producto  $producto
     * @return mixed
     */
    public function restore(User $usuario, Producto $producto)
    {
        return $this->tienePermisoParaEliminar($usuario, $producto);
    }

    /**
     * Determine whether the user can administrar los precios de los productos.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @return mixed
     */
    public function administrarPrecios(?User $usuario)
    {
        if (Auth::check() && $usuario->esEmpleado()) {
            return true;
        }
    }

    private function tienePermisoParaEliminar(User $usuario, Producto $producto)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'eliminar productos');
        }
    }
}
