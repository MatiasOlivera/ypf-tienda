<?php

namespace App\Http\Requests\UsersRequest;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

class UserUpdateRequest extends UserCreateRequest
{

    /**
     * Ignora el Unique Mail, para el Usuario que se esta actualizando.
     *
     */
    private function SetEmailRequest()
    {
        array_push($this->reglas['email'], Rule::unique('cliente_usuarios')->ignore($this->user->id));
    }

    private function eliminarPasswordRequest()
    {
        unset($this->reglas['password']);
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
        $this->SetEmailRequest();
        $this->eliminarPasswordRequest();
        return $this->reglas;
    }
}
