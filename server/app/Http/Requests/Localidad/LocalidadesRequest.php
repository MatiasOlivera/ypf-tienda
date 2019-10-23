<?php

namespace App\Http\Requests\Localidad;

use Illuminate\Validation\Rule;
use App\Http\Requests\PaginacionRequest;

class LocalidadesRequest extends PaginacionRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
