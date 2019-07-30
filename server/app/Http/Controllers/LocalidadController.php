<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Localidad, Provincia};
use App\Http\Requests\LocalidadRequest;
use App\Http\controllers\BaseController;
use App\Http\Resources\LocalidadCollection;
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
            $localidades = $provincia->localidades()->paginate($porPagina, ['*'], 'pagina');
            return new LocalidadCollection($localidades);
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
        $inputs = $request->only('nombre');
        $nombreLocalidad = $request->input('nombre');
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
     * @param  App\Http\Requests\LocalidadRequest  $request
     * @param  \App\Localidad  $localidad
     * @return \Illuminate\Http\Response
     */
    public function update(LocalidadRequest $request, Localidad $localidad)
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
