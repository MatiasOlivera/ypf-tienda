<?php

namespace App\Http\Controllers;

use App\{Localidad, Provincia};
use Illuminate\Http\Request;
use App\Http\Requests\LocalidadRequest;
use App\Http\controllers\BaseController;
use App\Auxiliares\{Respuesta, MensajeExito, MensajeError};


class LocalidadController extends Controller
{
    protected $baseController;
    protected $modeloSingular;
    protected $modeloPlural;

    public function __construct()
    {
        $this->modeloPlural     = 'localidades';
        $this->modeloSingular   = 'localidad';
        $this->baseController   = new BaseController($this->modeloSingular, $this->modeloPlural);
    }

    protected function setTextoMensaje(string $nombreLocalidad, string $nombreProvincia): string
    {
        return "La localidad {$nombreLocalidad}, {$nombreProvincia}";
    }

    /**
     * Display a listing of the resource.
     * @param  App\Provincia $provincia
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Provincia $provincia)
    {
        try {
            $porPagina      = $request->only('porPagina');
            $mails          = $provincia->localidades()->paginate($porPagina);
            $respuesta      = [$this->modeloPlural => $mails];
            return Respuesta::exito($respuesta, null, 200);
        } catch (\Throwable $th) {
            $mensajeError   = new MensajeError();
            $mensajeError->obtenerTodos($this->modeloPlural);
            return Respuesta::error($mensajeError, 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     * @param  App\Provincia $provincia
     * @param  App\Http\Requests\LocalidadRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocalidadRequest $request, Provincia $provincia)
    {
        $inputs['nombre'] = $request->input('localidad');
        $nombre = $this->setTextoMensaje($inputs['nombre'], $provincia->nombre);
        try {
            $localidad      = new Localidad($inputs);
            $provincia->localidades()->save($localidad);
            $respuesta      = [$this->modeloSingular  => $localidad,];

            $mensajeExito   = new MensajeExito();
            $mensajeExito->guardar($nombre);

            return Respuesta::exito($respuesta, $mensajeExito, 200);
        } catch (\Throwable $th) {
            $mensajeError   = new MensajeError();
            $mensajeError->guardar($nombre);
            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Localidad  $localidad
     * @return \Illuminate\Http\Response
     */
    public function show(Localidad $localidad)
    {
        $localidad->provincia;
        return $this->baseController->show($localidad);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\LocalidadRequest  $request
     * @param  \App\Localidad  $localidad
     * @return \Illuminate\Http\Response
     */
    public function update(LocalidadRequest $request, Localidad $localidad)
    {
        $provincia = $localidad->provincia;
        $inputs['nombre'] = $request->input('localidad');
        $nombre = $this->setTextoMensaje($localidad->nombre, $provincia->nombre);
        $parametros = [
            'inputs' => $inputs,
            'modelo' => $localidad,
        ];
        return $this->baseController->update($parametros, $nombre);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Localidad  $localidad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Localidad $localidad)
    {
        $provincia = $localidad->provincia;
        $nombre = $this->setTextoMensaje($localidad->nombre, $provincia->nombre);
        return $this->baseController->destroy($localidad, $nombre);
    }

    /**
     * Restaurar la localidad que ha sido eliminada
     *
     * @param  \App\Localidad  $localidad
     * @return \Illuminate\Http\Response
     */
    public function restore(Localidad $localidad)
    {
        $provincia = $localidad->provincia;
        $nombre = $this->setTextoMensaje($localidad->nombre, $provincia->nombre);
        return $this->baseController->restore($localidad, $nombre);
    }
}
