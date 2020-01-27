<?php

namespace App\Http\Resources\Pedido;

use App\Http\Resources\Utils\Paginacion;
use App\Http\Resources\Utils\RespuestaLimpia;
use App\Http\Resources\Pedido\PedidoListadoResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PedidoCollection extends ResourceCollection
{
    use RespuestaLimpia;
    use Paginacion;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'pedidos' => PedidoListadoResource::collection($this->collection),
            'paginacion' => $this->getPaginacion()
        ];
    }
}
