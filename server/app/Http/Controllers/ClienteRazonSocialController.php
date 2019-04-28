<?php

namespace App\Http\Controllers;

use App\{ Cliente, ClienteRazonSocial };
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Cliente\RazonSocial\ClienteRazonSocialRequest;
use App\Auxiliares\{ Respuesta, MensajeExito, MensajeError };

class ClienteRazonSocialController extends Controller
{
    protected $baseController;
    protected $modeloSingular;
    protected $modeloPlural;

    public function __Construct()
    {
        $this->modeloPlural     = 'RazonesSociales';
        $this->modeloSingular   = 'RazonSocial';
        $this->baseController   = new BaseController($this->modeloSingular, $this->modeloPlural);
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
            $razones        = $cliente->razonesSociales;
            $respuesta      = [$this->modeloPlural => $razones];
            return Respuesta::exito($respuesta, null, 200);
        } catch (\Throwable $th) {
            $mensajeError   = new MensajeError();
            $mensajeError->obtenerTodos('Razones Sociales');
            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param  App\Cliente $cliente
     * @param  App\Http\Requests\Cliente\RazonSocial\ClienteRazonSocialRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClienteRazonSocialRequest $request, Cliente $cliente)
    {
        $nombre  = $request->input('denominacion');
        try {
            $inputs         = $request->only('denominacion', 'cuit', 'localidad_id', 'calle', 'numero', 'area', 'telefono', 'mail');
            $razon          = $cliente->razonesSociales()->create($inputs);
            $mensajeExito   = new MensajeExito();
            $mensajeExito->guardar($nombre);
            $respuesta      = [$this->modeloSingular => $razon];
            return Respuesta::exito($respuesta, null, 200);
        } catch (\Throwable $th) {
            $mensajeError   = new MensajeError();
            $mensajeError->guardar($nombre);
            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function show(ClienteRazonSocial $razonSocial)
    {
        $razonSocial->localidad;
        return $this->baseController->show($razonSocial);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Cliente\RazonSocial\ClienteRazonSocialRequest  $request
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteRazonSocialRequest $request, ClienteRazonSocial $razonSocial)
    {
        $denominacionNew  = $request->input('denominacion');
        $inputs     = $request->only('denominacion', 'cuit', 'localidad_id', 'calle', 'numero', 'area', 'telefono', 'mail');
        $parametros = [
            'inputs' => $inputs,
            'modelo' => $razonSocial,
        ];
        return $this->baseController->update($parametros, $denominacionNew);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClienteRazonSocial $razonSocial)
    {
        $nombre  = $razonSocial->denominacion;
        return $this->baseController->destroy($razonSocial, $nombre);
    }

    /**
     * Restaurar la razonSocial que ha sido eliminada
     *
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function restore(ClienteRazonSocial $razonSocial)
    {
        $nombre  = $razonSocial->denominacion;
        return $this->baseController->restore($razonSocial, $nombre);
    }

    /**
     * Crear relacion estre cliente y razon
     * @param  App\Cliente $cliente
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function asociar(Cliente $cliente, ClienteRazonSocial $razonSocial)
    {
        $Exito      = "Se asocio con exito la Razon Social {$razonSocial->denominacion} al Cliente {$cliente->nombre}";
        $Error      = "No se pudo asociar la Razon Social {$razonSocial->denominacion} al Cliente {$cliente->nombre}";

        try {
            $razonId = $razonSocial->id_razon;
            $cliente->razonesSociales()->attach($razonId);
            $razonSocial->localidad;

            $mensajeExito   = new MensajeExito($Exito, 'ASOCIADOS');
            $respuesta      = [$this->modeloSingular => $razonSocial];

            return Respuesta::exito($respuesta, $mensajeExito, 200);
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError($Error, 'NO_ASOCIADOS');
            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * desasociar la relacion entre el cliente y la razon
     * @param  App\Cliente $cliente
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function desasociar(Cliente $cliente, ClienteRazonSocial $razonSocial)
    {
        $Exito = "Se ha desasociado la Razon Social {$razonSocial->denominacion} del Cliente {$cliente->nombre}";
        $Error = "No se pudo desasociar la Razon Social {$razonSocial->denominacion} del Cliente {$cliente->nombre}";

        try {
            $razonId = $razonSocial->id_razon;
            $cliente->razonesSociales()->detach($razonId);
            $razonSocial->localidad;

            $mensajeExito   = new MensajeExito($Exito, 'DESASOCIADOS');
            $respuesta      = [$this->modeloSingular => $razonSocial];

            return Respuesta::exito($respuesta, $mensajeExito, 200);
        } catch (\Throwable $th) {
            $mensajeError   = new MensajeError($Error, 'NO_DESASOCIADOS');
            return Respuesta::error($mensajeError, 500);
        }
    }
}
