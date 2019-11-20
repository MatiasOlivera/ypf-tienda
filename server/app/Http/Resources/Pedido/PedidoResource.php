<?php

namespace App\Http\Resources\Pedido;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Pedido\PedidoProductoResource;

class PedidoResource extends JsonResource
{
    public static $wrap = 'pedido';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fecha_pedido' => $this->fecha_pedido,
            'consumidor_final' => $this->consumidor_final,
            'plazo' => $this->plazo,
            'empleado' => $this->whenLoaded('empleado'),
            'cliente' => $this->whenLoaded('cliente'),
            'razon_social' => $this->whenLoaded('razonSocial'),
            'estado' => $this->whenLoaded('pedidoEstado'),
            'telefono' => $this->whenLoaded('telefono'),
            'domicilio' => $this->whenLoaded('domicilio'),
            'observacion' => $this->whenLoaded('observacion'),
            'productos' => PedidoProductoResource::collection($this->whenLoaded('productos'))
        ];
    }
}
