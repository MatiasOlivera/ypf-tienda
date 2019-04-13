<?php

namespace App\Http\Requests\Cliente\Cliente;

use Illuminate\Foundation\Http\FormRequest;

class ClienteCreateRequest extends FormRequest
{

    protected $reglas = [
        'documento'     => ['bail', 'integer',  'digits:8', 'unique:clientes,dni'],
        'nombre'        => ['bail', 'required', 'string', 'min:3', 'max:60',],
        'observacion'   => ['bail', 'string', 'min:2', 'max:200'],
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->reglas;
    }
}
