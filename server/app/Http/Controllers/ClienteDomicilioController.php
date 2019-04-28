<?php
namespace App\Http\Controllers;

use App\{ClienteDomicilio,Cliente};
use Illuminate\Http\Request;
use App\Http\Requests\Cliente\Domicilio\ClienteDomicilioRequest;
use App\Http\Controllers\BaseController;
use App\Auxiliares\{Respuesta, MensajeExito, MensajeError};

class ClienteDomicilioController extends Controller
{
    protected $baseController;
    protected $modeloSingular;
    protected $modeloPlural;

    public function __construct()
    {
        $this->modeloPlural     = 'domicilios';
        $this->modeloSingular   = 'domicilio';
        $this->baseController   = new BaseController($this->modeloSingular, $this->modeloPlural);
    }

    /**
     * Display a listing of the resource.
     * @param App\Cliente $cliente
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Cliente $cliente)
    {
        try {
            $domicilios = $cliente->domicilios;
            $respuesta = [$this->modeloPlural => $domicilios];
            return Respuesta::exito($respuesta, null, 200);
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->obtenerTodos($this->modeloPlural);
            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param App\Cliente $cliente
     * @param  App\Http\Requests\Cliente\Domicilio\ClienteDomicilioRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClienteDomicilioRequest $request, Cliente $cliente)
    {
        $inputs = $request->only('localidad_id', 'calle', 'numero', 'aclaracion');
        $nombre = "{$request->input('calle')}-{$request->input('numero')}";

        try {
            $domicilio = new ClienteDomicilio($inputs);
            $cliente->domicilios()->save($domicilio);
            $domicilio->localidad;
            $mensajeExito = new MensajeExito();
            $mensajeExito->guardar($nombre);
            $respuesta = [$this->modeloSingular => $domicilio];
            return Respuesta::exito($respuesta, $mensajeExito, 200);
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->guardar($nombre);
            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Display the specified resource.
     * @param  App\Cliente $cliente
     * @param  App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function show(ClienteDomicilio $domicilio)
    {
        $domicilio->localidad;
        return $this->baseController->show($domicilio);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Cliente\Domicilio\ClienteDomicilioRequest $request
     * @param  App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteDomicilioRequest $request, ClienteDomicilio $domicilio)
    {
        $inputs = $request->only('localidad_id', 'calle', 'numero', 'aclaracion');
        $nombre = "{$request->input('calle')}-{$request->input('numero')}";
        $parametros = [
            'inputs' => $inputs,
            'modelo' => $domicilio,
        ];
        return $this->baseController->update($parametros, $nombre);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClienteDomicilio $domicilio)
    {
        $nombre  = "{$domicilio->calle}-{$domicilio->numero}";
        return $this->baseController->destroy($domicilio, $nombre);
    }

    /**
     * Restaurar el domicilio que ha sido eliminado
     *
     * @param  App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function restore(ClienteDomicilio $domicilio)
    {
        $nombre  = "{$domicilio->calle}-{$domicilio->numero}";
        return $this->baseController->restore($domicilio, $nombre);
    }
}
