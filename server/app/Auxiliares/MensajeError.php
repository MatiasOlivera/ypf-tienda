<?php

namespace App\Auxiliares;

final class MensajeError extends Mensaje
{
    protected $tipo = 'error';

    public function __construct(string $descripcion = '', string $codigo = '')
    {
        parent::__construct($descripcion, $codigo);
    }

    public function guardar(string $nombre): void
    {
        $this->setDescripcion("$nombre no ha sido creado debido a un error interno");
        $this->setCodigo('NO_GUARDADO');
    }

    public function actualizar(string $nombre): void
    {
        $this->setDescripcion("$nombre no ha sido actualizado debido a un error interno");
        $this->setCodigo('NO_ACTUALIZADO');
    }

    public function eliminar(string $nombre): void
    {
        $this->setDescripcion("$nombre no ha sido eliminado debido a un error interno");
        $this->setCodigo('NO_ELIMINADO');
    }

    public function restaurar(string $nombre): void
    {
        $this->setDescripcion("$nombre no ha sido dado de alta debido a un error interno");
        $this->setCodigo('NO_RESTAURADO');
    }

    public function obtener(string $nombre): void
    {
        $this->setDescripcion("Hubo un error al consultar los datos $nombre");
        $this->setCodigo('NO_OBTENIDO');
    }

    public function obtenerTodos(string $nombre): void
    {
        $this->setDescripcion("Hubo un error al consultar el listado de $nombre");
        $this->setCodigo('NO_OBTENIDOS');
    }

    public function relacion(string $relacion): void
    {
        $this->setDescripcion("Hubo un error al consultar $relacion");
        $this->setCodigo('NO_OBTENIDO');
    }
}
