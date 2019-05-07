<?php

namespace App\Http\Controllers;

use App\Provincia;
use Illuminate\Http\Request;
use App\Http\controllers\BaseController;
use App\Auxiliares\{Respuesta, MensajeExito, MensajeError};

class ProvinciaController extends Controller
{
    protected $baseController;
    protected $modeloSingular;
    protected $modeloPlural;

    public function __construct()
    {
        $this->modeloPlural     = 'provincias';
        $this->modeloSingular   = 'provincia';
        $this->baseController   = new BaseController($this->modeloSingular, $this->modeloPlural);
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $provincia      = new Provincia;
            $provincias     = $provincia::all();
            $respuesta      = [$this->modeloPlural => $provincias];

            return Respuesta::exito($respuesta, null, 200);
        } catch (\Throwable $th) {
            $mensajeError   = new MensajeError();
            $mensajeError->obtenerTodos($this->modeloPlural);
            return Respuesta::error($mensajeError, 500);
        }
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
        return $this->baseController->store($parametros, $mensaje);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function show(Provincia $provincia)
    {
        return $this->baseController->show($provincia);
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
        return $this->baseController->update($parametros, lcfirst($mensaje));
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
        return $this->baseController->destroy($provincia, lcfirst($mensaje));
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
        return $this->baseController->restore($provincia, lcfirst($mensaje));
    }
}
