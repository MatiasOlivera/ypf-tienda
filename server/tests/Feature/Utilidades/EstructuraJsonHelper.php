<?php

namespace Tests\Feature\Utilidades;

trait EstructuraJsonHelper
{
    protected $estructuraPaginacion = [
        'paginacion' => [
            'total',
            'porPagina',
            'paginaActual',
            'ultimaPagina',
            'desde',
            'hasta',
            'rutas' => [
                'primeraPagina',
                'ultimaPagina',
                'siguientePagina',
                'paginaAnterior',
                'base'
            ]
        ]
    ];

    protected $estructuraMensaje = [
        'mensaje' => ['tipo', 'codigo', 'descripcion']
    ];
}
