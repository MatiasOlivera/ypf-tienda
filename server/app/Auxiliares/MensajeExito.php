<?php

namespace App\Auxiliares;

final class MensajeExito extends Mensaje
{
    protected $tipo = 'exito';

    public function __construct(string $descripcion = '', string $codigo = '')
    {
        parent::__construct($descripcion, $codigo);
    }

    public function guardar(string $nombre): void
    {
        $this->setDescripcion("$nombre se ha creado");
        $this->setCodigo('GUARDADO');
    }

    public function actualizar(string $nombre): void
    {
        $this->setDescripcion("$nombre se ha modificado");
        $this->setCodigo('ACTUALIZADO');
    }

    public function eliminar(string $nombre): void
    {
        $this->setDescripcion("$nombre se ha eliminado");
        $this->setCodigo('ELIMINADO');
    }

    public function restaurar(string $nombre): void
    {
        $this->setDescripcion("$nombre se ha dado de alta");
        $this->setCodigo('RESTAURADO');
    }
}
