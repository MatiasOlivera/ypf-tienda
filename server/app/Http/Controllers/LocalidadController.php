<?php

namespace App\Http\Controllers;

use App\{Localidad, Provincia};
use Illuminate\Http\Request;
use App\Http\Requests\Localidad\CrearLocalidadRequest;
use App\Http\controllers\BaseController;
use App\Auxiliares\{Respuesta, MensajeExito, MensajeError};


class LocalidadController extends Controller
{
    protected $baseController;
    protected $modeloSingular;
    protected $modeloPlural;
    protected $generoModelo;

    public function __construct()
    {
        $this->modeloPlural     = 'localidades';
        $this->modeloSingular   = 'localidad';
        $this->generoModelo = 'femenino';
        $this->baseController   = new BaseController($this->modeloSingular, $this->modeloPlural, $this->generoModelo);
    }

    protected function getNombre(string $nombreLocalidad, string $nombreProvincia): string
    {
        return "La localidad {$nombreLocalidad} - {$nombreProvincia}";
    }

    /**
     * Display a listing of the resource.
     * @param  App\Provincia $provincia
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Provincia $provincia)
    {
        try {
            $porPagina  = $request->only('porPagina');
            $provincias = $provincia->localidades()->paginate($porPagina);
            $respuesta  = [$this->modeloPlural => $provincias];
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
     * @param  App\Http\Requests\Localidad\CrearLocalidadRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrearLocalidadRequest $request)
    {
        $inputs = $request->only('nombre', 'provincia_id');
        $nombreLocalidad = $request->input('nombre');
        $provinciaId = $request->input('provincia_id');

        $provincia = Provincia::findOrFail($provinciaId);
        $nombre = $this->getNombre($nombreLocalidad, $provincia->nombre);

        try {
            $localidad      = new Localidad($inputs);
            $provincia->localidades()->save($localidad);

            $localidadGuardada = Localidad::findOrFail($localidad->id);
            $localidadGuardada->provincia;

            $respuesta = [$this->modeloSingular  => $localidadGuardada,];

            $mensajeExito   = new MensajeExito();
            $mensajeExito->guardar($nombre, $this->generoModelo);

            return Respuesta::exito($respuesta, $mensajeExito, 201);
        } catch (\Throwable $th) {
            $mensajeError   = new MensajeError();
            $mensajeError->guardar($nombre, $this->generoModelo);
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
     * @param  App\Http\Requests\Request  $request
     * @param  \App\Localidad  $localidad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Localidad $localidad)
    {
        $provincia = $localidad->provincia;
        $inputs = $request->only('nombre');

        $nombreLocalidad = $request->input('nombre');
        $nombres = [
            'exito' => $this->getNombre($nombreLocalidad, $provincia->nombre),
            'error' => $this->getNombre($localidad->nombre, $provincia->nombre)
        ];

        $parametros = [
            'inputs' => $inputs,
            'instancia' => $localidad,
        ];
        return $this->baseController->update($parametros, $nombres);
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
        $nombre = $this->getNombre($localidad->nombre, $provincia->nombre);
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
        $nombre = $this->getNombre($localidad->nombre, $provincia->nombre);
        return $this->baseController->restore($localidad, $nombre);
    }
}
