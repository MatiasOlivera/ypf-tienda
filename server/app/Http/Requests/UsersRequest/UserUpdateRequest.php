<?php

namespace App\Http\Requests\UsersRequest;

// use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Request;
// use App\User;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends UserCreateRequest
{
    private function SetEmailRequest()
    {
        // $this->reglas['email'][4] .= ",{$this->User->id}";
        // $this->reglas['email'][4] = Rule::unique('cliente_usuario')->ignore($this->User->id);

        array_push($this->reglas['email'], Rule::unique('cliente_usuario')->ignore($this->User->id));
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
        return $this->reglas;
    }
}
