<?php

namespace App\Http\Resources\Pedido;

use Illuminate\Http\Resources\Json\JsonResource;

class PedidoListadoResource extends JsonResource
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
        $localidad = $this->domicilio->localidad;

        return [
            'id' => $this->id,
            'empleado' => [
                'id' => $this->empleado->id,
                'nombre' => $this->empleado->nombre
            ],
            'fecha_pedido' => $this->fecha_pedido,
            'estado' => $this->estado,
            'entrega' => [
                'fecha' => $this->fecha_entrega,
                'estado' => $this->entregaEstado
            ],
            'consumidor_final' => $this->consumidor_final,
            'plazo' => $this->plazo,

            'telefono' => [
                'id' => $this->telefono_id
            ],

            'domicilio' => [
                'id' => $this->domicilio_id,
                'localidad' => [
                    'id' => $localidad->id,
                    'nombre' => $localidad->nombre
                ]
            ],

            'observacion' => [
                'id' => $this->observacion_id
            ],

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
