<?php

namespace App\Http\Requests\CategoriaProducto;

use Illuminate\Validation\Rule;
use App\Http\Requests\PaginacionRequest;

class CategoriasProductosRequest extends PaginacionRequest
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
                'descripcion',
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
