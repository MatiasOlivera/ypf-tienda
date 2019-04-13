<?php

namespace App\Http\Requests\Cliente\RazonSocial;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRazonSocialRequest extends FormRequest
{
    protected $reglas = [
        'nombre'        => ['bail', 'required', 'string', 'min:3', 'max:100'],
        'cuit'          => ['bail', 'required', 'integer', 'unique:razones_sociales,cuit', 'digit:11'],
        'localidad_id'  => ['bail', 'required', 'integer',],
        'calle'         => ['bail', 'string', 'min:3', 'max:70',],
        'altura'        => ['bail', 'integer', 'digits_between:1,4'],
        'email'         => ['bail', 'email', 'max:150',],
        'area'          => ['bail', 'integer', 'digits_between:2,5'],
        'telefono'      => ['bail', 'integer', 'digits_between:6,10'],

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
