<?php

namespace App\Auxiliares;

use Exception;

abstract class Mensaje
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

    final public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    final public function setCodigo(string $codigo): void
    {
        $this->codigo = $codigo;
    }

    abstract public function guardar(string $nombre): void;
    abstract public function actualizar(string $nombre): void;
    abstract public function eliminar(string $nombre): void;
    abstract public function restaurar(string $nombre): void;
}
