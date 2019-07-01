<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;

class CrearProductoRequest extends FormRequest
{
    protected $reglas = [
        'codigo' => ['bail', 'required', 'string', 'max:20'],
        'nombre' => ['bail', 'required', 'string', 'min:3', 'max:200'],
        'presentacion' => ['bail', 'required', 'string', 'min:3', 'max:60'],
        'id_categoria' => ['bail', 'required', 'integer', 'exists:categorias,ID_CAT_prod'],
        'precio_por_mayor' => ['bail', 'required', 'numeric', 'min:0'],
        'consumidor_final' => ['bail', 'required', 'numeric', 'min:0']
    ];

    private function setReglaCodigo()
    {
        array_push($this->reglas['codigo'], 'unique:productos,codigo_prod');
    }

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
        $this->setReglaCodigo();
        return $this->reglas;
    }
}
