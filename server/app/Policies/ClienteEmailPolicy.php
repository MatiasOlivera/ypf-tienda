<?php

namespace App\Policies;

use App\Cliente;
use App\ClienteMail;
use App\ClienteUsuario;
use Illuminate\Foundation\Auth\User;
use App\Policies\UsuarioTienePermiso;
use App\Policies\HayRelacionUsuarioYCliente;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClienteEmailPolicy
{
    use HandlesAuthorization;
    use UsuarioTienePermiso;
    use HayRelacionUsuarioYCliente;

    /**
     * Determine whether the user can view the clientes emails
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
     * Determine whether the user can view the cliente email.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteMail  $email
     * @return mixed
     */
    public function view(User $usuario, ClienteMail $email)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYClienteMail($usuario, $email);
        } else {
            return $this->tienePermiso($usuario, 'ver clientes');
        }
    }

    /**
     * Determine whether the user can create cliente emails.
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
     * Determine whether the user can update the cliente email.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteMail  $email
     * @return mixed
     */
    public function update(User $usuario, ClienteMail $email)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYClienteMail($usuario, $email);
        } else {
            return $this->tienePermiso($usuario, 'actualizar clientes');
        }
    }

    /**
     * Determine whether the user can delete the cliente email.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteMail  $email
     * @return mixed
     */
    public function delete(User $usuario, ClienteMail $email)
    {
        return $this->tienePermisoParaEliminar($usuario, $email);
    }

    /**
     * Determine whether the user can restore the cliente email.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\ClienteMail  $email
     * @return mixed
     */
    public function restore(User $usuario, ClienteMail $email)
    {
        return $this->tienePermisoParaEliminar($usuario, $email);
    }

    private function hayRelacionEntreClienteUsuarioYClienteMail(
        ClienteUsuario $usuario,
        ClienteMail $email
    ) {
        $hayRelacion = $usuario->id_cliente === $email->cliente_id;

        if (!$hayRelacion) {
            $this->deny('No tienes ninguna relación con el email del cliente');
        }

        return $hayRelacion;
    }

    private function tienePermisoParaEliminar(User $usuario, ClienteMail $email)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYClienteMail($usuario, $email);
        } else {
            return $this->tienePermiso($usuario, 'eliminar clientes');
        }
    }
}
