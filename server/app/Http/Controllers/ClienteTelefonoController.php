<?php

namespace App\Http\Controllers;

use App\{Cliente, ClienteTelefono};
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requscopeests\Cliente\Telefono\ClienteTelefonoRequest;
use App\Auxiliares\{Respuesta, MensajeExito, MensajeError};

class ClienteTelefonoController extends Controller
{
    protected $baseController;
    protected $modeloSingular;
    protected $modeloPlural;

    public function __construct()
    {
        $this->modeloPlural     = 'telefonos';
        $this->modeloSingular   = 'telefono';
        $this->baseController   = new BaseController($this->modeloSingular, $this->modeloPlural);
    }

    protected function setTextoMensaje(int $area, int $telefono, string $nombreContacto = null): string
    {
        return (is_null($nombreContacto)) ? "El telefono {$area} - {$telefono}" : "El telefono de {$nombreContacto}";
    }

    /**
     * Display a listing of the resource.
     * @param  App\Cliente $cliente
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Cliente $cliente)
    {
        try {
            $porPagina      = (is_numeric($request->input('porPagina'))) ? $request->input('porPagina') : 5;
            $telefonos      = $cliente->telefonos()->paginate($porPagina);
            $respuesta      = [$this->modeloPlural => $telefonos];

            return Respuesta::exito($respuesta, null, 200);
        } catch (\Throwable $th) {

            $mensajeError   = new MensajeError();
            $mensajeError->obtenerTodos($this->modeloPlural);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param  App\Cliente $cliente
     * @param  App\Http\Requests\Cliente\Telefono\ClienteTelefonoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Cliente $cliente)
    {
        $inputs = $request->only('area', 'telefono', 'nombreContacto');

        $telefonoMensaje = $this->setTextoMensaje($inputs['area'], $inputs['telefono'], $inputs['nombreContacto']);
        try {
            $telefono = new ClienteTelefono($inputs);
            $cliente->telefonos()->save($telefono);
            $respuesta      = [$this->modeloSingular => $telefono];

            $mensajeExito   = new MensajeExito();
            $mensajeExito->guardar($telefonoMensaje);

            return Respuesta::exito($respuesta, $mensajeExito, 200);
        } catch (\Throwable $th) {

            $mensajeError   = new MensajeError();
            $mensajeError->guardar(lcfirst($telefonoMensaje));

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ClienteTelefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function show(ClienteTelefono $telefono)
    {
        return $this->baseController->show($telefono);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Cliente\Telefono\ClienteTelefonoRequest  $request
     * @param  \App\ClienteTelefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteTelefonoRequest $request, ClienteTelefono $telefono)
    {
        $inputs = $request->only('area', 'telefono', 'nombreContacto');
        $telefonoMensaje = $this->setTextoMensaje($inputs['area'], $inputs['telefono'], $inputs['nombreContacto']);
        $parametros = [
            'inputs' => $inputs,
            'modelo' => $telefono,
        ];
        return $this->baseController->update($parametros, $telefonoMensaje);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ClienteTelefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClienteTelefono $telefono)
    {
        $telefonoMensaje = $this->setTextoMensaje($telefono->area, $telefono->telefono, $telefono->nombreContacto);
        return $this->baseController->destroy($telefono, $telefonoMensaje);
    }

    /**
     * Restaurar el Telefono que ha sido eliminado
     *
     * @param  \App\ClienteTelefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function restore(ClienteTelefono $telefono)
    {
        $telefonoMensaje = $this->setTextoMensaje($telefono->area, $telefono->telefono, $telefono->nombreContacto);
        return $this->baseController->restore($telefono, $telefonoMensaje);
    }
}
