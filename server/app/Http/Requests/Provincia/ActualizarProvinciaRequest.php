<?php

namespace App\Http\Requests\Provincia;

use Illuminate\Validation\Rule;

class ActualizarProvinciaRequest extends ProvinciaRequest
{
    protected function getReglaNombre(): array
    {
        $regla = parent::getReglaNombre();
        $regla[] = Rule::unique('provincias', 'nom_provincia')
            ->ignore($this->provincia->id, 'id_provincia');
        return $regla;
    }
}
