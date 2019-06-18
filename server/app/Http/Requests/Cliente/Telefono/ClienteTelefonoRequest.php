<?php

namespace App\Http\Requests\Cliente\Telefono;

use Illuminate\Foundation\Http\FormRequest;

class ClienteTelefonoRequest extends FormRequest
{
    protected $reglas = [
        'area'          => ['bail', 'required', 'integer', 'digits_between:2,5'],
        'telefono'      => ['bail', 'required', 'integer', 'digits_between:6,10'],
        'nombreContacto' => ['bail', 'string', 'max:60',],
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
