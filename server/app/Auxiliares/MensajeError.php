<?php

namespace App\Auxiliares;

final class MensajeError extends Mensaje
{
    protected $tipo = 'error';

    public function __construct(string $descripcion, string $codigo)
    {
        parent::__construct($descripcion, $codigo);
    }
}
