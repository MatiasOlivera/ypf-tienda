<?php

namespace App\Http\Requests\Producto;

use Illuminate\Validation\Rule;
use App\Http\Requests\CastingDeTipos;
use App\Http\Requests\PaginacionRequest;

class ProductosRequest extends PaginacionRequest
{
    use CastingDeTipos;

    protected $soloFavoritos = ['bail', 'nullable', 'boolean'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function setOrdenarPor()
    {
        array_push(
            $this->ordenarPor,
            Rule::in([
                'codigo',
                'nombre',
                'precio_por_mayor',
                'consumidor_final',
                'created_at',
                'updated_at',
                'deleted_at'
            ])
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setOrdenarPor();
        $reglas = parent::rules();

        // Solo favoritos
        $reglas['soloFavoritos'] = $this->soloFavoritos;

        return $reglas;
    }

    /**
     * Casting de los parámetros de la ruta (query string)
     * @return array
     */
    public function all($claves = null)
    {
        $parametros = parent::all();

        // Solo favoritos
        $soloFavoritos = $this->query('soloFavoritos');
        $parametros['soloFavoritos'] = $this->getBooleano($soloFavoritos);

        return $parametros;
    }
}
