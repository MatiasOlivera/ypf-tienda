<?php

namespace App\Auxiliares;

final class MensajeExito extends Mensaje
{
    protected $tipo = 'exito';

    public function __construct(string $descripcion, string $codigo)
    {
        parent::__construct($descripcion, $codigo);
    }
}
