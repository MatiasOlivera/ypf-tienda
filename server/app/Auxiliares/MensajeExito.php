<?php

namespace App\Auxiliares;

final class MensajeExito extends Mensaje
{
    protected $tipo = 'exito';

    public function __construct(string $descripcion = '', string $codigo = '')
    {
        parent::__construct($descripcion, $codigo);
    }

    public function guardar(string $nombre, string $genero): void
    {
        $this->setDescripcionSegunGenero("$nombre ha sido creado", "$nombre ha sido creada", $genero);
        $this->setCodigo('GUARDADO');
    }

    public function actualizar(string $nombre, string $genero): void
    {
        $this->setDescripcionSegunGenero("$nombre ha sido modificado", "$nombre ha sido modificada", $genero);
        $this->setCodigo('ACTUALIZADO');
    }

    public function eliminar(string $nombre, string $genero): void
    {
        $this->setDescripcionSegunGenero("$nombre ha sido eliminado", "$nombre ha sido eliminada", $genero);
        $this->setCodigo('ELIMINADO');
    }

    public function restaurar(string $nombre, string $genero): void
    {
        $this->setDescripcionSegunGenero("$nombre ha sido dado de alta", "$nombre ha sido dada de alta", $genero);
        $this->setCodigo('RESTAURADO');
    }
}
