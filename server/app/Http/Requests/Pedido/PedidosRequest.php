<?php

namespace App\Http\Requests\Pedido;

use Illuminate\Validation\Rule;
use App\Http\Requests\PaginacionRequest;

class PedidosRequest extends PaginacionRequest
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
        $reglas = parent::rules();
        $entregaEstado = $this->getReglaPedidoEntregaEstado();
        return array_merge($reglas, $entregaEstado);
    }

    public function setOrdenarPor()
    {
        array_push(
            $this->ordenarPor,
            Rule::in([
                'empleado_id',
                'cliente_id',
                'razon_id',
                'fecha_pedido',
                'created_at',
                'updated_at'
            ])
        );
    }

    public function getReglaPedidoEntregaEstado()
    {
        return [
            'entrega_estado_id' => ['bail', 'string', Rule::in(['pendiente'])]
        ];
    }
}
