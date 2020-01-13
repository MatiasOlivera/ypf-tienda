<?php

namespace App\Policies;

use App\Cliente;
use App\ClienteRazonSocial;
use App\ClienteUsuario;
use Illuminate\Foundation\Auth\User;
use App\Policies\UsuarioTienePermiso;
use App\Policies\HayRelacionUsuarioYCliente;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClienteRazonSocialPolicy
{
    use HandlesAuthorization;
    use UsuarioTienePermiso;
    use HayRelacionUsuarioYCliente;

    /**
     * Determine whether the user can view the cliente razones sociales.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param App\Cliente $cliente
     * @return mixed
     */
    public function index(User $usuario, Cliente $cliente)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYCliente($usuario, $cliente);
        } else {
            return $this->tienePermiso($usuario, 'ver clientes');
        }
    }

    /**
     * Determine whether the user can view the cliente razon social.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return mixed
     */
    public function view(User $usuario, ClienteRazonSocial $razonSocial)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYClienteRazonSocial($usuario, $razonSocial);
        } else {
            return $this->tienePermiso($usuario, 'ver clientes');
        }
    }

    /**
     * Determine whether the user can create cliente razon socials.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param App\Cliente $cliente
     * @return mixed
     */
    public function create(User $usuario, Cliente $cliente)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYCliente($usuario, $cliente);
        } else {
            return $this->tienePermiso($usuario, 'crear clientes');
        }
    }

    /**
     * Determine whether the user can update the cliente razon social.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return mixed
     */
    public function update(User $usuario, ClienteRazonSocial $razonSocial)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYClienteRazonSocial($usuario, $razonSocial);
        } else {
            return $this->tienePermiso($usuario, 'actualizar clientes');
        }
    }

    /**
     * Determine whether the user can delete the cliente razon social.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return mixed
     */
    public function delete(User $usuario, ClienteRazonSocial $razonSocial)
    {
        return $this->tienePermisoParaEliminar($usuario, $razonSocial);
    }

    /**
     * Determine whether the user can restore the cliente razon social.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return mixed
     */
    public function restore(User $usuario, ClienteRazonSocial $razonSocial)
    {
        return $this->tienePermisoParaEliminar($usuario, $razonSocial);
    }

    /**
     * Determine whether the user can asociar el cliente y la razón social
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  App\Cliente $cliente
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return mixed
     */
    public function asociar(User $usuario, Cliente $cliente, ClienteRazonSocial $razonSocial)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYCliente($usuario, $cliente);
        } else {
            return $this->tienePermiso($usuario, 'actualizar clientes');
        }
    }

    /**
     * Determine whether the user can desasociar el cliente y la razón social
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  App\Cliente $cliente
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return mixed
     */
    public function desasociar(User $usuario, Cliente $cliente, ClienteRazonSocial $razonSocial)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYCliente($usuario, $cliente);
        } else {
            return $this->tienePermiso($usuario, 'actualizar clientes');
        }
    }

    private function hayRelacionEntreClienteUsuarioYClienteRazonSocial(
        ClienteUsuario $usuario,
        ClienteRazonSocial $razonSocial
    ) {
        $cliente = $razonSocial->clientes()->wherePivot('id_cliente', $usuario->id_cliente)->first();
        $hayRelacion = !is_null($cliente);

        if (!$hayRelacion) {
            $this->deny('No tienes ninguna relación con la razón social del cliente');
        }

        return $hayRelacion;
    }

    private function tienePermisoParaEliminar(User $usuario, ClienteRazonSocial $razonSocial)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYClienteRazonSocial($usuario, $razonSocial);
        } else {
            return $this->tienePermiso($usuario, 'eliminar clientes');
        }
    }
}
