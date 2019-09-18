<?php

namespace App\Http\Requests\Cotizacion;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CotizacionRequest extends FormRequest
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
            'empleado_id' => $this->getReglaEmpleadoId(),
            'cliente_id' => $this->getReglaClienteId(),
            'razon_id' => $this->getReglaRazonId(),
            'fecha_pedido' => $this->getReglaFechaPedido(),
            'consumidor_final' => $this->getReglaConsumidorFinal(),
            'plazo' => $this->getReglaPlazo(),
            'telefono_id' => $this->getReglaTelefonoId(),
            'domicilio_id' => $this->getReglaDomicilioId(),
            'pedido_id' => $this->getReglaPedido(),
            'observacion' => $this->getReglaObservacion(),
            'productos' => $this->getReglaProductos(),
            'productos.*.id' => $this->getReglaProductoId(),
            'productos.*.cantidad' => $this->getReglaProductoCantidad(),
            'productos.*.precio' => $this->getReglaProductoPrecio()
        ];
    }

    protected function getReglaEmpleadoId(): array
    {
        return ['bail', 'required', 'integer', Rule::exists('usuarios', 'ID_ven')];
    }

    protected function getReglaClienteId(): array
    {
        return ['bail', 'required', 'integer', Rule::exists('clientes', 'id_cliente')];
    }

    protected function getReglaRazonId(): array
    {
        return ['bail', 'required', 'integer', Rule::exists('razones_sociales', 'id_razon')];
    }

    protected function getReglaFechaPedido(): array
    {
        return ['bail', 'date'];
    }

    protected function getReglaConsumidorFinal(): array
    {
        return ['bail', 'required', 'boolean'];
    }

    protected function getReglaPlazo(): array
    {
        return ['bail', 'string', 'min:2', 'max:60'];
    }

    protected function getReglaTelefonoId(): array
    {
        return ['bail', 'required', 'integer', Rule::exists('con_cliente', 'id')];
    }

    protected function getReglaDomicilioId(): array
    {
        return ['bail', 'required', 'integer', Rule::exists('dom_cliente', 'id_dom')];
    }

    protected function getReglaPedido(): array
    {
        return ['bail', 'integer', Rule::exists('pedido', 'id_pedido')];
    }

    protected function getReglaObservacion(): array
    {
        return ['bail', 'string', 'max:140'];
    }

    protected function getReglaProductos(): array
    {
        return ['bail', 'required'];
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
