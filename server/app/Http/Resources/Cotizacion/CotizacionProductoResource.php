<?php

namespace App\Http\Resources\Cotizacion;

use App\Http\Resources\ProductoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CotizacionProductoResource extends JsonResource
{
    public static $wrap = 'cotizacionProducto';

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
            'deleted_at' => $this->deleted_at,
            'producto' => new ProductoResource($this->whenLoaded('producto')),
            'cotizacion' => [
                'id' => $this->cotizacion_id
            ],
        ];
    }
}
