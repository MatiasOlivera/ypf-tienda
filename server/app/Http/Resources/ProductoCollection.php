<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductoResource;
use App\Http\Resources\Utils\Paginacion;
use App\Http\Resources\Utils\RespuestaLimpia;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductoCollection extends ResourceCollection
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
            'productos' => ProductoResource::collection($this->collection),
            'paginacion' => $this->getPaginacion()
        ];
    }
}
