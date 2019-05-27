<?php

namespace App\Http\Requests\Cliente\RazonSocial;

use Illuminate\Validation\Rule;

class ClienteRazonSocialUpdateRequest extends ClienteRazonSocialRequest
{
    private function setReglaCuit()
    {
        $razonSocial =  $this->razonSocial;
        $this->reglas['cuit'][3] .= ',' . $razonSocial->id . ',id_razon';
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
        $this->setReglaCuit();
        return $this->reglas;
    }
}
