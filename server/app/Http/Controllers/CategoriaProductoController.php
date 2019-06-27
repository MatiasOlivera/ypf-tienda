<?php

namespace App\Http\Controllers;

use App\CategoriaProducto;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requests\CategoriaProducto\CategoriasProductosRequest;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
