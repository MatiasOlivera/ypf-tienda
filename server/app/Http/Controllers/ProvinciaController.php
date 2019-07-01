<?php

namespace App\Http\Controllers;

use App\Provincia;
use Illuminate\Http\Request;
use App\Http\controllers\BaseController;
use App\Auxiliares\{Respuesta, MensajeError};

class ProvinciaController extends Controller
{
    protected $baseController;
    protected $modeloSingular;
    protected $modeloPlural;
    protected $generoModelo;

    public function __construct()
    {
        $this->modeloPlural     = 'provincias';
        $this->modeloSingular   = 'provincia';
        $this->generoModelo = 'femenino';
        $this->baseController   = new BaseController($this->modeloSingular, $this->modeloPlural, $this->generoModelo);
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
        $inputs = $request->only('nombre');
        $nombre = "La provincia {$request->input('nombre')}";
        $parametros = [
            'inputs' => $inputs,
            'modelo' => 'Provincia',
        ];
        return $this->baseController->store($parametros, $nombre);
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
        $inputs = $request->only('nombre');
        $parametros = [
            'inputs' => $inputs,
            'instancia' => $provincia,
        ];

        $nombres = [
            "exito" => "La provincia {$request->input('nombre')}",
            "error" => "La provincia {$provincia->nombre}"
        ];
        return $this->baseController->update($parametros, $nombres);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provincia $provincia)
    {
        $nombre = "La provincia {$provincia->nombre}";
        return $this->baseController->destroy($provincia, $nombre);
    }

    /**
     * Restaurar la provincia que ha sido eliminada
     *
     *@param  \App\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function restore(Provincia $provincia)
    {
        $nombre = "La provincia {$provincia->nombre}";
        return $this->baseController->restore($provincia, $nombre);
    }
}
