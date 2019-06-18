<?php

namespace App\Http\Controllers;

use App\{Cliente, ClienteTelefono};
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Cliente\Telefono\ClienteTelefonoRequest;
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
        return (is_null($nombreContacto)) ? "El teléfono {$area}-{$telefono}" : "El teléfono de {$nombreContacto}";
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
            $porPagina      = $request->input('porPagina');
            $telefonos      = $cliente->telefonos()->paginate($porPagina);
            $respuesta      = [$this->modeloPlural => $telefonos];

            return Respuesta::exito($respuesta, null, 200);
        } catch (\Throwable $th) {
            $mensajeError   = new MensajeError();
            $mensajeError->obtenerTodos("teléfonos del cliente");

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param  App\Http\Requests\Cliente\Telefono\ClienteTelefonoRequest  $request
     * @param  App\Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function store(ClienteTelefonoRequest $request, Cliente $cliente)
    {
        $inputs = $request->only('area', 'telefono');
        $nombreContacto = $request->input('nombreContacto', null);

        $telefonoMensaje = $this->setTextoMensaje($inputs['area'], $inputs['telefono'], $nombreContacto);
        try {
            $telefono = new ClienteTelefono($inputs);
            $cliente->telefonos()->save($telefono);
            $respuesta      = [$this->modeloSingular => $telefono];

            $mensajeExito   = new MensajeExito();
            $mensajeExito->guardar($telefonoMensaje);

            return Respuesta::exito($respuesta, $mensajeExito, 200);
        } catch (\Throwable $th) {
            $mensajeError   = new MensajeError();
            $mensajeError->guardar($telefonoMensaje);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Cliente $cliente
     * @param  \App\ClienteTelefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente, ClienteTelefono $telefono)
    {
        return $this->baseController->show($telefono);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Cliente\Telefono\ClienteTelefonoRequest  $request
     * @param  App\Cliente $cliente
     * @param  \App\ClienteTelefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteTelefonoRequest $request, Cliente $cliente, ClienteTelefono $telefono)
    {
        $inputs = $request->only('area', 'telefono');
        $nombreContacto = $request->input('nombreContacto', null);

        $telefonoMensajes = [
            'exito' => $this->setTextoMensaje($inputs['area'], $inputs['telefono'], $nombreContacto),
            'error' => $this->setTextoMensaje($telefono->area, $telefono->telefono, $telefono->nombreContacto)
        ];

        $parametros = [
            'inputs' => array_merge($inputs, ['nombreContacto' => $nombreContacto]),
            'instancia' => $telefono,
        ];
        return $this->baseController->update($parametros, $telefonoMensajes);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Cliente $cliente
     * @param  \App\ClienteTelefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente, ClienteTelefono $telefono)
    {
        $telefonoMensaje = $this->setTextoMensaje($telefono->area, $telefono->telefono, $telefono->nombreContacto);
        return $this->baseController->destroy($telefono, $telefonoMensaje);
    }

    /**
     * Restaurar el Telefono que ha sido eliminado
     *
     * @param  App\Cliente $cliente
     * @param  \App\ClienteTelefono  $telefono
     * @return \Illuminate\Http\Response
     */
    public function restore(Cliente $cliente, ClienteTelefono $telefono)
    {
        $telefonoMensaje = $this->setTextoMensaje($telefono->area, $telefono->telefono, $telefono->nombreContacto);
        return $this->baseController->restore($telefono, $telefonoMensaje);
    }
}
