<?php

namespace App\Http\Resources\Pedido;

use App\Http\Resources\ProductoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoProductoResource extends JsonResource
{
    public static $wrap = 'pedidoProducto';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($reaquest)
    {
        return [
            'id' => $this->id,
            'cantidad' => $this->cantidad,
            'precio' => $this->precio,
            'producto' => new ProductoResource($this->whenLoaded('producto')),
            'pedido' => [
                'id' => $this->pedido_id
            ],
        ];
    }
}
