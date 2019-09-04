<?php

namespace App\Http\Resources\Cotizacion;

use Illuminate\Http\Resources\Json\JsonResource;

class CotizacionListadoResource extends JsonResource
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
            'empleado' => [
                'id' => $this->empleado->id,
                'nombre' => $this->empleado->nombre
            ],
            'fecha_pedido' => $this->fecha_pedido,
            'estado' => $this->cotizacionEstado,
            'consumidor_final' => $this->consumidor_final,
            'plazo' => $this->plazo,

            'telefono' => [
                'id' => $this->telefono_id
            ],

            'domicilio' => [
                'id' => $this->domicilio_id
            ],

            'pedido' => [
                'id' => $this->pedido_id
            ],

            'observacion' => [
                'id' => $this->observacion_id
            ],

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            // FIXME: debería mostrar los campos cliente y razon social
            // solo cuando el usuario logueado es el vendedor
            'cliente' => [
                'id' => $this->cliente->id,
                'nombre' => $this->cliente->nombre
            ],
            'razon_social' => [
                'id' => $this->razonSocial->id,
                'denominacion' => $this->razonSocial->denominacion
            ],
        ];
    }
}
