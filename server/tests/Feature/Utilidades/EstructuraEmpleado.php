<?php

namespace Tests\Feature\Utilidades;

trait EstructuraEmpleado
{
    private $atributosEmpleado = [
        'id',
        'documento',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'sexo',
        'password',
        'cargo_id'
    ];
}
