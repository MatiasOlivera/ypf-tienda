<?php

namespace App\Http\Resources\Cotizacion;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Cotizacion\CotizacionProductoResource;

class CotizacionResource extends JsonResource
{
    public static $wrap = 'cotizacion';

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
            'pedido_id' => $this->pedido_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'empleado' => $this->whenLoaded('empleado'),
            'cliente' => $this->whenLoaded('cliente'),
            'razon_social' => $this->whenLoaded('razonSocial'),
            'estado' => $this->whenLoaded('cotizacionEstado'),
            'telefono' => $this->whenLoaded('telefono'),
            'domicilio' => $this->whenLoaded('domicilio'),
            'observacion' => $this->whenLoaded('observacion'),
            'productos' => CotizacionProductoResource::collection($this->whenLoaded('productos'))
        ];
    }
}
