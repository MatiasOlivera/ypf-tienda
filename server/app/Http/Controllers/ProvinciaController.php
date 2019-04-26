<?php

namespace App\Http\Controllers;

use App\Provincia;
use Illuminate\Http\Request;
use App\Http\controllers\BaseController;

class ProvinciaController extends Controller
{
    protected $BaseController;
    protected $modeloSingular;
    protected $modeloPlural;

    public function __Construct()
    {
        $this->modeloPlural     = 'provincias';
        $this->modeloSingular   = 'provincia';
        $this->BaseController   = new BaseController($this->modeloSingular, $this->modeloPlural);
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = [
            'modelo'            => 'Provincia',
            'campos'            => ['id', 'nombre', 'created_at', 'updated_at', 'deleted_at',],
            'relaciones'        => null,
            'buscar'            => $request->input("buscar", null),
            'eliminados'        => $request->input("eliminados", false),
            'paginado'  => [
                'porPagina'     => $request->input("porPagina", 10),
                'ordenadoPor'   => $request->input("ordenadoPor", 'cliente'),
                'orden'         => $request->input("orden", true),
            ]
        ];
        return $this->BaseController->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs   = $request->input('nombre');
        $mensaje = "la provincia {$inputs['nombre']}";
        $parametros = [
            'inputs' => $inputs,
            'modelo' => 'Provincia',
        ];
        return $this->BaseController->store($parametros, $mensaje);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function show(Provincia $provincia)
    {
        return $this->BaseController->show($provincia);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Provincia $provincia)
    {
        $inputs['nombre'] = $request->input('provincia');
        $mensaje = "la provincia {$inputs['nombre']}";
        $parametros = [
            'inputs' => $inputs,
            'modelo' => $provincia,
        ];
        return $this->BaseController->update($parametros, lcfirst($mensaje));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provincia $provincia)
    {
        //mensajes
        $mensaje = "la provincia {$provincia->nombre}";
        return $this->BaseController->destroy($provincia, lcfirst($mensaje));
    }

    /**
     * Restaurar la provincia que ha sido eliminada
     *
     *@param  \App\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function restore(Provincia $provincia)
    {
        $mensaje = "la provincia {$provincia->nombre}";
        return $this->BaseController->restore($provincia, lcfirst($mensaje));
    }
}
