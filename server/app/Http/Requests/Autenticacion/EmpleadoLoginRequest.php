<?php

namespace App\Http\Requests\Autenticacion;

use Illuminate\Foundation\Http\FormRequest;

class EmpleadoLoginRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'documento' => $this->getReglaDocumento(),
            'password' => $this->getReglaPassword()
        ];
    }

    protected function getReglaDocumento()
    {
        return ['bail', 'required', 'integer', 'digits:8'];
    }

    protected function getReglaPassword()
    {
        return ['bail', 'required', 'string', 'min:8'];
    }
}
