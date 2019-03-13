<?php

namespace App\Http\Requests\UsersRequest;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends UserLoginRequest
{
    private function setReglaName()
    {
        $this->reglas['name'] = ['bail', 'required', 'string', 'max:50', ];
    }

    private function setReglaUniqueEmail()
    {
        array_push($this->reglas['email'], 'unique:cliente_usuarios,email');
    }

    /**
     * Agrega la confirmacion de PASSWORD a la hs de crear un Usuario
     */
    private function setReglaPasswordConfirm()
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
        $this->setReglaName();
        $this->setReglaUniqueEmail();
        $this->setReglaPasswordConfirm();
        return $this->reglas;
    }
}
