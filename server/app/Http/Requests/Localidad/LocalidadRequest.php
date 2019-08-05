<?php

namespace App\Http\Requests\Localidad;

use Illuminate\Foundation\Http\FormRequest;

class LocalidadRequest extends FormRequest
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
        return $this->getReglaNombre();
    }

    protected function getReglaNombre(): array
    {
        return [
            'nombre' => ['bail', 'required', 'string', 'max:60']
        ];
    }
}
