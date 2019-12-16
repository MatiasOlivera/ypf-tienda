<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EmpleadoPermiso extends Pivot
{
    protected $fillable = [
        'ver',
        'crear',
        'editar',
        'borrar'
    ];
}
