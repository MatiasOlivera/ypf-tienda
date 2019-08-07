<?php

namespace App\Http\Resources;

use App\Http\Resources\Utils\Paginacion;
use App\Http\Resources\Utils\RespuestaLimpia;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LocalidadCollection extends ResourceCollection
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
            'localidades' => $this->collection,
            'paginacion' => $this->getPaginacion()
        ];
    }
}
