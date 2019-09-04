<?php

namespace App\Http\Resources\Cotizacion;

use App\Http\Resources\Utils\Paginacion;
use App\Http\Resources\Utils\RespuestaLimpia;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Cotizacion\CotizacionListadoResource;

class CotizacionCollection extends ResourceCollection
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
            'cotizaciones' => CotizacionListadoResource::collection($this->collection),
            'paginacion' => $this->getPaginacion()
        ];
    }
}
