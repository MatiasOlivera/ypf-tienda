<?php

namespace App\Http\Resources;

use App\Http\Resources\Utils\RespuestaLimpia;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClienteTelefonoCollection extends ResourceCollection
{
    use RespuestaLimpia;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['telefonos' => $this->collection];
    }
}
