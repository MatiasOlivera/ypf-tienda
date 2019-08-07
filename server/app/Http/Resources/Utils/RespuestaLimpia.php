<?php

namespace App\Http\Resources\Utils;

use Illuminate\Http\Resources\Json\JsonResource;

trait RespuestaLimpia
{
    /**
     * Sobreescribir el método `toResponse` de la clase `ResourceCollection`
     * que agrega los metadatos relacionados con la paginación (links, meta)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        // No envolver el objeto con la clave `data`
        JsonResource::withoutWrapping();

        return JsonResource::toResponse($request);
    }
}
