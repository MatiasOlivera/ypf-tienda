<?php

namespace App\Policies;

use App\Cliente;
use App\ClienteUsuario;
use App\ClienteDomicilio;
use Illuminate\Foundation\Auth\User;
use App\Policies\UsuarioTienePermiso;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClienteDomicilioPolicy
{
    use HandlesAuthorization;
    use UsuarioTienePermiso;

    /**
     * Determine whether the user can view the clientes.
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
     * Determine whether the user can view the cliente domicilio.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteDomicilio  $domicilio
     * @return mixed
     */
    public function view(User $usuario, ClienteDomicilio $domicilio)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYClienteDomicilio($usuario, $domicilio);
        } else {
            return $this->tienePermiso($usuario, 'ver clientes');
        }
    }

    /**
     * Determine whether the user can create cliente domicilios.
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
     * Determine whether the user can update the cliente domicilio.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteDomicilio  $domicilio
     * @return mixed
     */
    public function update(User $usuario, ClienteDomicilio $domicilio)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYClienteDomicilio($usuario, $domicilio);
        } else {
            return $this->tienePermiso($usuario, 'actualizar clientes');
        }
    }

    /**
     * Determine whether the user can delete the cliente domicilio.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteDomicilio  $domicilio
     * @return mixed
     */
    public function delete(User $usuario, ClienteDomicilio $domicilio)
    {
        return $this->tienePermisoParaEliminar($usuario, $domicilio);
    }

    /**
     * Determine whether the user can restore the cliente domicilio.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteDomicilio  $domicilio
     * @return mixed
     */
    public function restore(User $usuario, ClienteDomicilio $domicilio)
    {
        return $this->tienePermisoParaEliminar($usuario, $domicilio);
    }

    private function hayRelacionEntreClienteUsuarioYCliente(ClienteUsuario $usuario, Cliente $cliente)
    {
        $hayRelacion = $usuario->id_cliente === $cliente->id;

        if (!$hayRelacion) {
            $this->deny('No tienes ninguna relación con el cliente');
        }

        return $hayRelacion;
    }

    private function hayRelacionEntreClienteUsuarioYClienteDomicilio(
        ClienteUsuario $usuario,
        ClienteDomicilio $domicilio
    ) {
        $hayRelacion = $usuario->id_cliente === $domicilio->cliente_id;

        if (!$hayRelacion) {
            $this->deny('No tienes ninguna relación con el domicilio del cliente');
        }

        return $hayRelacion;
    }

    private function tienePermisoParaEliminar(User $usuario, ClienteDomicilio $domicilio)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYClienteDomicilio($usuario, $domicilio);
        } else {
            return $this->tienePermiso($usuario, 'eliminar clientes');
        }
    }
}
