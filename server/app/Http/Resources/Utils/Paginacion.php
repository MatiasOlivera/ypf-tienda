<?php

namespace App\Http\Resources\Utils;

use Illuminate\Http\Resources\Json\JsonResource;

trait Paginacion
{
    /**
     * Obtener el objeto paginación
     *
     * @return array
     */
    private function getPaginacion(): array
    {
        return [
            "total" => $this->total(),
            "porPagina" => $this->perPage(),
            "paginaActual" => $this->currentPage(),
            "ultimaPagina" => $this->lastPage(),
            "desde" => $this->firstItem(),
            "hasta" => $this->lastItem()
        ];
    }
}
