<?php

namespace App\Http\Requests\CategoriaProducto;

use Illuminate\Validation\Rule;

class ActualizarCategoriaProductoRequest extends CrearCategoriaProductoRequest
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

    private function setReglaDescripcion()
    {
        $unico = Rule::unique('categorias', 'desc_cat')
            ->ignore($this->categoriaProducto->id, 'ID_CAT_prod');

        array_push($this->reglas['descripcion'], $unico);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setReglaDescripcion();
        return $this->reglas;
    }
}
