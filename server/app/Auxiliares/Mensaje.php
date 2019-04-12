<?php

namespace App\Auxiliares;

class Mensaje
{
    protected $tipo;
    private $codigo;
    private $descripcion;

    public function __construct(string $descripcion, string $codigo)
    {
        $this->descripcion = $descripcion;
        $this->codigo = $codigo;
    }

    final public function getObjeto(): array
    {
        return [
            'tipo' => $this->tipo,
            'codigo' => $this->codigo,
            'descripcion' => $this->descripcion
        ];
    }
}
