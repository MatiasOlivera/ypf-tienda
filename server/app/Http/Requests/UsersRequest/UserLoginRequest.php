<?php

namespace App\Http\Requests\UsersRequest;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    protected $reglas = [
        'email' => ['bail', 'required', 'email',  'max:80'],
        'password' => ['bail', 'required', 'string', 'min:8'],
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
