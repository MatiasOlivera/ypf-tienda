<?php

namespace App\Http\Requests\Cliente\Domicilio;

use Illuminate\Foundation\Http\FormRequest;

class ClienteDomicilioRequest extends FormRequest
{
    protected $reglas = [
        'localidad_id'  => ['bail', 'required', 'integer',],
        'calle'         => ['bail', 'required', 'string', 'max:70'],
        'numero'        => ['bail', 'required', 'integer', 'digits_between:1,4',],
        'aclaracion'    => ['bail', 'string', 'min:3', 'max:200',],
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
