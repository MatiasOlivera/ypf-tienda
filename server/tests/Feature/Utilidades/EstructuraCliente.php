<?php

namespace Tests\Feature\Utilidades;

trait EstructuraCliente
{
    private $atributosCliente = [
        'id',
        'nombre',
        'documento',
        'observacion',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
