<?php

namespace App\Auxiliares;

use Exception;
use Illuminate\Contracts\Support\Jsonable;

abstract class Mensaje implements Jsonable
{
    protected $tipo;
    private $codigo;
    private $descripcion;

    public function __construct(string $descripcion, string $codigo)
    {
        $this->descripcion = $descripcion;
        $this->codigo = $codigo;
    }

    public function toJson($options = 0)
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

    private function setGenero(string $genero): void
    {
        if ($genero !== 'masculino' && $genero !== 'femenino') {
            throw new Exception('El género debe ser masculino o femenino');
        }

        $this->genero = $genero;
    }

    final public function setDescripcionSegunGenero(
        string $descMasculino,
        string $descFemenino,
        ?string $genero = 'masculino'
    ): void {
        $this->setGenero($genero);
        $this->descripcion = $this->genero === 'masculino' ? $descMasculino : $descFemenino;
    }

    abstract public function guardar(string $nombre, string $genero): void;
    abstract public function actualizar(string $nombre, string $genero): void;
    abstract public function eliminar(string $nombre, string $genero): void;
    abstract public function restaurar(string $nombre, string $genero): void;
}
