<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\{ Cliente, ClienteRazonSocial };
use App\Http\Resources\ClienteRazonSocialCollection;
use App\Auxiliares\{ Respuesta, MensajeExito, MensajeError };
use App\Http\Requests\Cliente\RazonSocial\ClienteRazonSocialRequest;
use App\Http\Requests\Cliente\RazonSocial\ClienteRazonSocialUpdateRequest;

class ClienteRazonSocialController extends Controller
{
    protected $baseController;
    protected $modeloSingular;
    protected $modeloPlural;
    protected $generoModelo;

    public function __construct()
    {
        $this->modeloPlural     = 'razonesSociales';
        $this->modeloSingular   = 'razonSocial';
        $this->generoModelo = 'femenino';
        $this->baseController   = new BaseController($this->modeloSingular, $this->modeloPlural, $this->generoModelo);
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
            $razones = $cliente->razonesSociales;
            return new ClienteRazonSocialCollection($razones);
        } catch (\Throwable $th) {
            $mensajeError   = new MensajeError();
            $mensajeError->obtenerTodos('razones sociales');
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
        $nombre = "La razón social {$request->input('denominacion')}";
        try {
            $inputs         = $request->only(
                'denominacion',
                'cuit',
                'localidad_id',
                'calle',
                'numero',
                'area',
                'telefono',
                'email'
            );
            $razon          = $cliente->razonesSociales()->create($inputs);

            $razonGuardada = ClienteRazonSocial::findOrFail($razon->id);
            $razonGuardada->localidad;

            $mensajeExito = new MensajeExito();
            $mensajeExito->guardar($nombre, $this->generoModelo);
            $respuesta = [$this->modeloSingular => $razonGuardada];
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
     * @param  App\Cliente $cliente
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente, ClienteRazonSocial $razonSocial)
    {
        $razonSocial->localidad;
        return $this->baseController->show($razonSocial);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Cliente\RazonSocial\ClienteRazonSocialUpdateRequest  $request
     * @param  App\Cliente $cliente
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteRazonSocialUpdateRequest $request, Cliente $cliente, ClienteRazonSocial $razonSocial)
    {
        $nombres = [
            'exito' => "La razón social {$request->input('denominacion')}",
            'error' => "La razón social {$razonSocial->denominacion}"
        ];
        $inputs     = $request->only(
            'denominacion',
            'cuit',
            'localidad_id',
            'calle',
            'numero',
            'area',
            'telefono',
            'email'
        );
        $parametros = [
            'inputs' => $inputs,
            'instancia' => $razonSocial,
        ];
        return $this->baseController->update($parametros, $nombres);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Cliente $cliente
     * @param  App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente, ClienteRazonSocial $razonSocial)
    {
        $nombre = "La razón social $razonSocial->denominacion";
        return $this->baseController->destroy($razonSocial, $nombre);
    }

    /**
     * Restaurar la razonSocial que ha sido eliminada
     *
     * @param  App\Cliente $cliente
     * @param  \App\ClienteRazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function restore(Cliente $cliente, ClienteRazonSocial $razonSocial)
    {
        $nombre = "La razón social $razonSocial->denominacion";
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
        $exito = "Se asoció con éxito la razón social {$razonSocial->denominacion} al cliente {$cliente->nombre}";
        $error = "No se pudo asociar la razón social {$razonSocial->denominacion} al cliente {$cliente->nombre}";

        try {
            $razonId = $razonSocial->id_razon;
            $cliente->razonesSociales()->attach($razonId);
            $razonSocial->localidad;

            $mensajeExito   = new MensajeExito($exito, 'ASOCIADOS');
            $respuesta      = [$this->modeloSingular => $razonSocial];

            return Respuesta::exito($respuesta, $mensajeExito, 200);
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError($error, 'NO_ASOCIADOS');
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
        $exito = "Se ha desasociado la razón social {$razonSocial->denominacion} del cliente {$cliente->nombre}";
        $error = "No se pudo desasociar la razón social {$razonSocial->denominacion} del cliente {$cliente->nombre}";

        try {
            $razonId = $razonSocial->id_razon;
            $cliente->razonesSociales()->detach($razonId);
            $razonSocial->localidad;

            $mensajeExito   = new MensajeExito($exito, 'DESASOCIADOS');
            $respuesta      = [$this->modeloSingular => $razonSocial];

            return Respuesta::exito($respuesta, $mensajeExito, 200);
        } catch (\Throwable $th) {
            $mensajeError   = new MensajeError($error, 'NO_DESASOCIADOS');
            return Respuesta::error($mensajeError, 500);
        }
    }
}
