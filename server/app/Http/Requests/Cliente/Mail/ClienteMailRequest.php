<?php

namespace App\Http\Requests\Cliente\Mail;

use Illuminate\Foundation\Http\FormRequest;

class ClienteMailRequest extends FormRequest
{
    protected $reglas = [
        'mail'      => ['bail', 'required', 'email',],
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
