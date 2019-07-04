<?php

namespace App\Http\Requests\Producto;

use Illuminate\Validation\Rule;

class ActualizarProductoRequest extends CrearProductoRequest
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

    private function setReglaCodigo()
    {
        $unico = Rule::unique('productos', 'codigo_prod')
            ->ignore($this->producto->id, 'id');

        array_push($this->reglas['codigo'], $unico);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setReglaCodigo();
        return $this->reglas;
    }
}
