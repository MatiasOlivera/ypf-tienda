<?php

namespace App\Http\Requests\CategoriaProducto;

use Illuminate\Foundation\Http\FormRequest;

class CrearCategoriaProductoRequest extends FormRequest
{
    protected $reglas = [
        'descripcion' => ['bail', 'required', 'string', 'min:3', 'max:200',]
    ];

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
        array_push($this->reglas['descripcion'], 'unique:categorias,desc_cat');
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
