<?php

namespace App\Http\Requests\Localidad;

use Illuminate\Foundation\Http\FormRequest;

class CrearLocalidadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $nombre = $this->getReglaNombre();
        $provinciaId = $this->getReglaProvinciaId();
        return array_merge($nombre, $provinciaId);
    }

    protected function getReglaNombre(): array
    {
        return [
            'nombre' => ['bail', 'required', 'string', 'max:60']
        ];
    }

    protected function getReglaProvinciaId(): array
    {
        return [
            'provincia_id' => ['bail', 'required', 'integer', 'exists:provincias,id_provincia']
        ];
    }
}
