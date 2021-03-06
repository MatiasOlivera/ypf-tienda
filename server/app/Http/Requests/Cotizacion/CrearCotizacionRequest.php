<?php

namespace App\Http\Requests\Cotizacion;

use App\Http\Requests\CamposPeticion;

class CrearCotizacionRequest extends CotizacionRequest
{
    use CamposPeticion;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return parent::rules();
    }

    protected function getReglaCotizacionProductoId(): array
    {
        return ['bail', 'required', 'integer', Rule::exists('detalle_cot', 'id_det')];
    }
}
