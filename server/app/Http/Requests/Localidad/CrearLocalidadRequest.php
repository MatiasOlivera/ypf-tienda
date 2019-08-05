<?php

namespace App\Http\Requests\Localidad;

class CrearLocalidadRequest extends LocalidadRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $reglas = parent::rules();
        $provinciaId = $this->getReglaProvinciaId();
        return array_merge($reglas, $provinciaId);
    }

    protected function getReglaProvinciaId(): array
    {
        return [
            'provincia_id' => ['bail', 'required', 'integer', 'exists:provincias,id_provincia']
        ];
    }
}
