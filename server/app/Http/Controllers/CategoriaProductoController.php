<?php

namespace App\Http\Controllers;

use App\CategoriaProducto;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requests\CategoriaProducto\CategoriasProductosRequest;
use App\Http\Requests\CategoriaProducto\CrearCategoriaProductoRequest;

class CategoriaProductoController extends Controller
{
    private $controladorBase;

    public function __construct()
    {
        $this->controladorBase = new BaseController('categoria', 'categorias');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoriasProductosRequest $request)
    {
        $parametros = [
            'modelo' => 'CategoriaProducto',
            'campos' => [
                'id',
                'descripcion',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
            'relaciones' => null,
            'buscar' => $request->input('buscar', null),
            'eliminados' => $request->input('eliminados', false),
            'paginado' => [
                'porPagina' => $request->input('porPagina', 10),
                'ordenarPor' => $request->input('ordenarPor', 'descripcion'),
                'orden' => $request->input('orden', 'ASC'),
            ]
        ];

        return $this->controladorBase->index($parametros, 'las categorias');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CrearCategoriaProductoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrearCategoriaProductoRequest $request)
    {
        $inputs = $request->only('descripcion');
        $nombre = "La categoria {$request->input('descripcion')}";

        $parametros = [
            'inputs' => $inputs,
            'modelo' => 'CategoriaProducto',
        ];

        return $this->controladorBase->store($parametros, $nombre);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CategoriaProducto  $categoriaProducto
     * @return \Illuminate\Http\Response
     */
    public function show(CategoriaProducto $categoriaProducto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CategoriaProducto  $categoriaProducto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoriaProducto $categoriaProducto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoriaProducto  $categoriaProducto
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoriaProducto $categoriaProducto)
    {
        //
    }
}
