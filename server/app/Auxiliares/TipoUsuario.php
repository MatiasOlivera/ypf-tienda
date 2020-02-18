<?php

namespace App\Auxiliares;

trait TipoUsuario
{
    /**
     * El usuario es un cliente?
     *
     * @return boolean
     */
    public function esCliente(): bool
    {
        return $this->guard === 'cliente';
    }

    /**
     * El usuario es un empleado?
     *
     * @return boolean
     */
    public function esEmpleado(): bool
    {
        return $this->guard === 'empleado';
    }
}
