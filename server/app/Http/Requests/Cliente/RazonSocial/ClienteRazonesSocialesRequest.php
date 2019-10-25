<?php

namespace App\Http\Requests\Cliente\RazonSocial;

use Illuminate\Validation\Rule;
use App\Http\Requests\PaginacionRequest;

class ClienteRazonesSocialesRequest extends PaginacionRequest
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
        $this->setOrdenarPor();
        return parent::rules();
    }

    public function setOrdenarPor()
    {
        array_push($this->ordenarPor, Rule::in(['denominacion', 'cuit', 'localidad_id',]));
    }
}
