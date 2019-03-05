<?php

namespace App\Http\Requests\UsersRequest;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends UserLoginRequest
{
    private function SetNameRequest()
    {
        $name = array('bail', 'required', 'string', 'max:50');

        $this->reglas['name'] = $name;
    }

    private function SetUniqueEmailRequest()
    {
        array_push($this->reglas['email'], 'unique:cliente_usuarios,email');
    }

    /**
     * Agrega la confirmacion de PASSWORD a la hs de crear un Usuario
     */
    private function SetPasswordConfirmRequest()
    {
        array_push($this->reglas['password'], 'confirmed');
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
        $this->SetNameRequest();
        $this->SetUniqueEmailRequest();
        $this->SetPasswordConfirmRequest();
        return $this->reglas;
    }
}