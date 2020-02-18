<?php

namespace App\Policies;

use App\Cliente;
use App\ClienteUsuario;
use App\ClienteTelefono;
use Illuminate\Foundation\Auth\User;
use App\Policies\UsuarioTienePermiso;
use App\Policies\HayRelacionUsuarioYCliente;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClienteTelefonoPolicy
{
    use HandlesAuthorization;
    use UsuarioTienePermiso;
    use HayRelacionUsuarioYCliente;

    /**
     * Determine whether the user can view the cliente telefonos.
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
     * Determine whether the user can view the cliente telefono.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteTelefono  $telefono
     * @return mixed
     */
    public function view(User $usuario, ClienteTelefono $telefono)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYClienteTelefono($usuario, $telefono);
        } else {
            return $this->tienePermiso($usuario, 'ver clientes');
        }
    }

    /**
     * Determine whether the user can create cliente telefonos.
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
     * Determine whether the user can update the cliente telefono.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteTelefono  $telefono
     * @return mixed
     */
    public function update(User $usuario, ClienteTelefono $telefono)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYClienteTelefono($usuario, $telefono);
        } else {
            return $this->tienePermiso($usuario, 'actualizar clientes');
        }
    }

    /**
     * Determine whether the user can delete the cliente telefono.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteTelefono  $telefono
     * @return mixed
     */
    public function delete(User $usuario, ClienteTelefono $telefono)
    {
        return $this->tienePermisoParaEliminar($usuario, $telefono);
    }

    /**
     * Determine whether the user can restore the cliente telefono.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteTelefono  $telefono
     * @return mixed
     */
    public function restore(User $usuario, ClienteTelefono $telefono)
    {
        return $this->tienePermisoParaEliminar($usuario, $telefono);
    }

    private function hayRelacionEntreClienteUsuarioYClienteTelefono(
        ClienteUsuario $usuario,
        ClienteTelefono $telefono
    ) {
        $hayRelacion = $usuario->id_cliente === $telefono->cliente_id;

        if (!$hayRelacion) {
            $this->deny('No tienes ninguna relación con el teléfono del cliente');
        }

        return $hayRelacion;
    }

    private function tienePermisoParaEliminar(User $usuario, ClienteTelefono $telefono)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYClienteTelefono($usuario, $telefono);
        } else {
            return $this->tienePermiso($usuario, 'eliminar clientes');
        }
    }
}
