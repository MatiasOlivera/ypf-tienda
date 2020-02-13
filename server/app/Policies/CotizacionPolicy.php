<?php

namespace App\Policies;

use App\Cliente;
use App\Cotizacion;
use App\ClienteUsuario;
use Illuminate\Foundation\Auth\User;
use App\Policies\UsuarioTienePermiso;
use App\Policies\HayRelacionUsuarioYCliente;
use Illuminate\Auth\Access\HandlesAuthorization;

class CotizacionPolicy
{
    use UsuarioTienePermiso;
    use HandlesAuthorization;
    use HayRelacionUsuarioYCliente;

    /**
     * Determine whether the user can view the cotizaciones.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @return mixed
     */
    public function list(User $usuario)
    {
        if ($usuario->esCliente()) {
            return false;
        } else {
            return $this->tienePermiso($usuario, 'ver cotizaciones');
        }
    }

    /**
     * Determine whether the user can view the cotizaciones.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  App\Cliente $cliente
     * @return mixed
     */
    public function listByClienteId(User $usuario, Cliente $cliente)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYCliente($usuario, $cliente);
        } else {
            return $this->tienePermiso($usuario, 'ver cotizaciones');
        }
    }

    /**
     * Determine whether the user can view the cotización.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Cotizacion  $cotizacion
     * @return mixed
     */
    public function view(User $usuario, Cotizacion $cotizacion)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioyCotizacion($usuario, $cotizacion);
        } else {
            return $this->tienePermiso($usuario, 'ver cotizaciones');
        }
    }

    /**
     * Determine whether the user can create cotizaciones.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  App\Cliente $cliente
     * @return mixed
     */
    public function create(User $usuario, Cliente $cliente)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioYCliente($usuario, $cliente);
        } else {
            return $this->tienePermiso($usuario, 'crear cotizaciones');
        }
    }

    /**
     * Determine whether the user can update the cotización.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Cotizacion  $cotizacion
     * @return mixed
     */
    public function update(User $usuario, Cotizacion $cotizacion)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioyCotizacion($usuario, $cotizacion);
        } else {
            return $this->tienePermiso($usuario, 'actualizar cotizaciones');
        }
    }

    /**
     * Determine whether the user can delete the cotización.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\Cotizacion  $cotizacion
     * @return mixed
     */
    public function delete(User $usuario, Cotizacion $cotizacion)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioyCotizacion($usuario, $cotizacion);
        } else {
            return $this->tienePermiso($usuario, 'eliminar cotizaciones');
        }
    }

    private function hayRelacionEntreClienteUsuarioyCotizacion(ClienteUsuario $usuario, Cotizacion $cotizacion)
    {
        $hayRelacion = $usuario->id_cliente === $cotizacion->cliente->id;

        if (!$hayRelacion) {
            $this->deny('No tienes ninguna relación con el cliente de la cotización');
        }

        return $hayRelacion;
    }
}
