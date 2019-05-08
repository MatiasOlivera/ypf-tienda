<?php

namespace App\Http\Requests\Cliente\Cliente;

use Illuminate\Validation\Rule;
use App\Http\Requests\PaginacionRequest;

class ClientesRequest extends PaginacionRequest
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
                'nombre',
                'documento',
                'created_at',
                'updated_at',
                'deleted_at',
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
