<?php

namespace App\Auxiliares;

final class MensajeError extends Mensaje
{
    protected $tipo = 'error';

    public function __construct(string $descripcion = '', string $codigo = '')
    {
        parent::__construct($descripcion, $codigo);
    }

    public function guardar(string $nombre, string $genero): void
    {
        $descMasculino = "$nombre no ha sido creado debido a un error interno";
        $descFemenino = "$nombre no ha sido creada debido a un error interno";
        $this->setDescripcionSegunGenero($descMasculino, $descFemenino, $genero);
        $this->setCodigo('NO_GUARDADO');
    }

    public function actualizar(string $nombre, string $genero): void
    {
        $descMasculino = "$nombre no ha sido actualizado debido a un error interno";
        $descFemenino = "$nombre no ha sido actualizada debido a un error interno";
        $this->setDescripcionSegunGenero($descMasculino, $descFemenino, $genero);
        $this->setCodigo('NO_ACTUALIZADO');
    }

    public function eliminar(string $nombre, string $genero): void
    {
        $descMasculino = "$nombre no ha sido eliminado debido a un error interno";
        $descFemenino = "$nombre no ha sido eliminada debido a un error interno";
        $this->setDescripcionSegunGenero($descMasculino, $descFemenino, $genero);
        $this->setCodigo('NO_ELIMINADO');
    }

    public function restaurar(string $nombre, string $genero): void
    {
        $descMasculino = "$nombre no ha sido dado de alta debido a un error interno";
        $descFemenino = "$nombre no ha sido dada de alta debido a un error interno";
        $this->setDescripcionSegunGenero($descMasculino, $descFemenino, $genero);
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
