<?php

namespace App\Http\Requests;

use App\Http\Requests\CastingDeTipos;
use Illuminate\Foundation\Http\FormRequest;

class PaginacionRequest extends FormRequest
{
    use CastingDeTipos;

    protected $buscar = ['bail', 'nullable', 'string', 'max:25'];
    protected $eliminados = ['bail', 'nullable', 'boolean'];
    protected $pagina = ['bail', 'nullable', 'integer', 'min:1'];
    protected $porPagina = ['bail', 'nullable', 'integer', 'min:1', 'max:25'];
    protected $ordenarPor = ['bail', 'nullable', 'alpha'];
    protected $orden = ['bail', 'nullable', 'in:asc,desc'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'buscar' => $this->buscar,
            'eliminados' => $this->eliminados,
            'pagina' => $this->pagina,
            'porPagina' => $this->porPagina,
            'ordenarPor' => $this->ordenarPor,
            'orden' => $this->orden
        ];
    }

    /**
     * Casting de los parámetros de la ruta (query string)
     * @return array
     */
    public function all($claves = null)
    {
        $buscar = $this->query('buscar');
        $eliminados = $this->query('eliminados');
        $pagina = $this->query('pagina');
        $porPagina = $this->query('porPagina');
        $ordenarPor = $this->query('ordenarPor');
        $orden = $this->query('orden');

        $input = [
            'buscar' => $this->getCadena($buscar),
            'eliminados' => $this->getBooleano($eliminados),
            'pagina' => $this->getEntero($pagina),
            'porPagina' => $this->getEntero($porPagina),
            'ordenarPor' => $ordenarPor,
            'orden' => $orden
        ];

        return $input;
    }
}
