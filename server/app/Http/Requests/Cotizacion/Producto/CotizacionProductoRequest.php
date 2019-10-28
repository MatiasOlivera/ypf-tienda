<?php

namespace App\Http\Requests\Cotizacion\Producto;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CotizacionProductoRequest extends FormRequest
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
            'productos' => $this->getReglaProductos(),
            'productos.*.id' => $this->getReglaCotizacionProductoId(),
            'productos.*.producto_id' => $this->getReglaProductoId(),
            'productos.*.cantidad' => $this->getReglaProductoCantidad(),
            'productos.*.precio' => $this->getReglaProductoPrecio()
        ];
    }

    protected function getReglaProductos(): array
    {
        return ['bail', 'required', 'array'];
    }

    protected function getReglaCotizacionProductoId(): array
    {
        return ['bail', 'nullable', 'integer', Rule::exists('detalle_cot', 'id_det')];
    }

    protected function getReglaProductoId(): array
    {
        return ['bail', 'required', 'integer', Rule::exists('productos', 'id')];
    }

    protected function getReglaProductoCantidad(): array
    {
        return ['bail', 'required', 'numeric', 'min:1'];
    }

    protected function getReglaProductoPrecio(): array
    {
        return ['bail', 'numeric', 'min:1'];
    }
}
