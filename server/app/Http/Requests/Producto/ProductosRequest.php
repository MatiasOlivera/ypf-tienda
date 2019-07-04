<?php

namespace App\Http\Requests\Producto;

use Illuminate\Validation\Rule;
use App\Http\Requests\PaginacionRequest;

class ProductosRequest extends PaginacionRequest
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

    public function setOrdenarPor()
    {
        array_push(
            $this->ordenarPor,
            Rule::in([
                'codigo',
                'nombre',
                'precio_por_mayor',
                'consumidor_final',
                'created_at',
                'updated_at',
                'deleted_at'
            ])
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setOrdenarPor();
        return parent::rules();
    }
}
