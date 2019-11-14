<?php

namespace App\Http\Requests\Pedido;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CrearPedidoRequest extends FormRequest
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
            'cotizacion_id' => $this->getReglaCotizacionId()
        ];
    }

    protected function getReglaCotizacionId(): array
    {
        return ['bail', 'required', 'integer', Rule::exists('cotizacion', 'id_cot')];
    }
}
