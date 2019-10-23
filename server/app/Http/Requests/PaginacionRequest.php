<?php

namespace App\Http\Requests;

use App\Http\Traits\CastFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class PaginacionRequest extends FormRequest
{
    use CastFormRequest;

    protected $buscar = ['bail', 'nullable', 'string', 'max:25'];
    protected $eliminados = ['bail', 'nullable', 'boolean'];
    protected $pagina = ['bail', 'nullable', 'integer', 'min:1'];
    protected $porPagina = ['bail', 'nullable', 'integer', 'min:1', 'max:25'];
    protected $ordenarPor = ['bail', 'nullable', 'alpha_dash'];
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

    public function casts(): array
    {
        return [
            'eliminados' => 'boolean',
            'pagina' => 'integer',
            'porPagina' => 'integer'
        ];
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
}
