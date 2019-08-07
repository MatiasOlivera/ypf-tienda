<?php

namespace Tests\Feature\Utilidades;

trait EstructuraProducto
{
    private $estructuraProducto = [
        'producto' => [
            'id',
            'codigo',
            'nombre',
            'presentacion',
            'precio_por_mayor',
            'consumidor_final',
            'imagen',
            'es_favorito',
            'id_categoria',
            'created_at',
            'updated_at',
            'deleted_at'
        ]
    ];

    private function getEstructuraProducto(): array
    {
        return array_merge($this->estructuraProducto, $this->estructuraMensaje);
    }
}
