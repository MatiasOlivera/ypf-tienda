<?php

namespace Tests\Feature\Utilidades;

trait EstructuraProducto
{
    private $atributosProducto = [
        'id',
        'codigo',
        'nombre',
        'presentacion',
        'precio_por_mayor',
        'consumidor_final',
        'imagen',
        'id_categoria',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

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

    private function getEstructuraProductoComoCliente(): array
    {
        return [
            'producto' => [
                'id',
                'codigo',
                'nombre',
                'presentacion',
                'imagen',
                'es_favorito',
                'id_categoria',
                'created_at',
                'updated_at',
                'deleted_at'
            ]
        ];
    }

    private function getEstructuraProductoComoEmpleado(): array
    {
        return [
            'producto' => [
                'id',
                'codigo',
                'nombre',
                'presentacion',
                'precio_por_mayor',
                'consumidor_final',
                'imagen',
                'id_categoria',
                'created_at',
                'updated_at',
                'deleted_at'
            ]
        ];
    }
}
