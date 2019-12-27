<?php

namespace App\Policies;

use Illuminate\Foundation\Auth\User;

trait UsuarioTienePermiso
{
    private function tienePermiso(User $usuario, string $permiso)
    {
        $tienePermiso = $usuario->hasPermissionTo($permiso);

        if (!$tienePermiso) {
            /**
             * Illuminate\Auth\Access\HandlesAuthorization -> deny()
             */
            $this->deny("No tienes permiso para: $permiso");
        }

        return $tienePermiso;
    }
}
