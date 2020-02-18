<?php

namespace App\Policies;

use App\ClienteUsuario;
use App\CotizacionProducto;
use Illuminate\Foundation\Auth\User;
use App\Policies\UsuarioTienePermiso;
use Illuminate\Auth\Access\HandlesAuthorization;

class CotizacionProductoPolicy
{
    use UsuarioTienePermiso;
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the cotización producto.
     *
     * @param  Illuminate\Foundation\Auth\User  $usuario
     * @param  \App\CotizacionProducto  $cotizacionProducto
     * @return mixed
     */
    public function delete(User $usuario, CotizacionProducto $cotizacionProducto)
    {
        if ($usuario->esCliente()) {
            return $this->hayRelacionEntreClienteUsuarioyCotizacionProducto($usuario, $cotizacionProducto);
        } else {
            return $this->tienePermiso($usuario, 'eliminar cotizaciones');
        }
    }

    private function hayRelacionEntreClienteUsuarioyCotizacionProducto(
        ClienteUsuario $usuario,
        CotizacionProducto $cotizacionProducto
    ) {
        $hayRelacion = $usuario->id_cliente === $cotizacionProducto->cotizacion->cliente_id;

        if (!$hayRelacion) {
            $this->deny('No tienes ninguna relación con el cliente de la cotización');
        }

        return $hayRelacion;
    }
}
