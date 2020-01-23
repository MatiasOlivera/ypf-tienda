<?php

namespace App\Policies;

use App\CategoriaProducto;
use Illuminate\Foundation\Auth\User;
use App\Policies\UsuarioTienePermiso;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoriaProductoPolicy
{
    use UsuarioTienePermiso;
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the categorias de producto.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @return mixed
     */
    public function list(?User $usuario)
    {
        return true;
    }

    /**
     * Determine whether the user can view the categoria producto.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\CategoriaProducto  $categoriaProducto
     * @return mixed
     */
    public function view(?User $usuario, CategoriaProducto $categoriaProducto)
    {
        return true;
    }

    /**
     * Determine whether the user can create categorias de producto.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @return mixed
     */
    public function create(User $usuario)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'crear categorias de producto');
        }
    }

    /**
     * Determine whether the user can update the categoria producto.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\CategoriaProducto  $categoriaProducto
     * @return mixed
     */
    public function update(User $usuario, CategoriaProducto $categoriaProducto)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'actualizar categorias de producto');
        }
    }

    /**
     * Determine whether the user can delete the categoria producto.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\CategoriaProducto  $categoriaProducto
     * @return mixed
     */
    public function delete(User $usuario, CategoriaProducto $categoriaProducto)
    {
        return $this->tienePermisoParaEliminar($usuario, $categoriaProducto);
    }

    /**
     * Determine whether the user can restore the categoria producto.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\CategoriaProducto  $categoriaProducto
     * @return mixed
     */
    public function restore(User $usuario, CategoriaProducto $categoriaProducto)
    {
        return $this->tienePermisoParaEliminar($usuario, $categoriaProducto);
    }

    private function tienePermisoParaEliminar(User $usuario, CategoriaProducto $categoriaProducto)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'eliminar categorias de producto');
        }
    }
}
