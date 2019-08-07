<?php

namespace App\Http\Requests\Provincia;

use Illuminate\Validation\Rule;

class CrearProvinciaRequest extends ProvinciaRequest
{
    protected function getReglaNombre(): array
    {
        $regla = parent::getReglaNombre();
        $regla[] = Rule::unique('provincias', 'nom_provincia');
        return $regla;
    }
}
