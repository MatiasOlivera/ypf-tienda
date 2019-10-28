<?php

namespace App\Http\Requests\Cotizacion;

use Illuminate\Validation\Rule;
use App\Http\Requests\CamposPeticion;
use App\Http\Requests\Cotizacion\CotizacionRequest;

class ActualizarCotizacionRequest extends CotizacionRequest
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
}
