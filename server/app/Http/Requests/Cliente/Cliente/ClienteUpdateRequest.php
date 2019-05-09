<?php

namespace App\Http\Requests\Cliente\Cliente;

use Illuminate\Validation\Rule;

class ClienteUpdateRequest extends ClienteCreateRequest
{
    private function setReglaDni()
    {
        $cliente =  $this->cliente;
        $this->reglas['documento'][3] .= ',' . $cliente->id . ',id_cliente';
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
        $this->setReglaDni();
        return $this->reglas;
    }
}
