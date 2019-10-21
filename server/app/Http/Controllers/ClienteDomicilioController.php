<?php
namespace App\Http\Controllers;

use App\Auxiliares\Consulta;
use Illuminate\Http\Request;
use App\{ClienteDomicilio,Cliente};
use App\Http\Controllers\BaseController;
use App\Http\Resources\ClienteDomicilioCollection;
use App\Auxiliares\{Respuesta, MensajeExito, MensajeError};
use App\Http\Requests\Cliente\Domicilio\ClienteDomicilioRequest;
use App\Http\Requests\Cliente\Domicilio\ClienteDomiciliosRequest;

class ClienteDomicilioController extends Controller
{
    protected $baseController;
    protected $modeloSingular;
    protected $modeloPlural;
    protected $generoModelo;

    public function __construct()
    {
        $this->modeloPlural     = 'domicilios';
        $this->modeloSingular   = 'domicilio';
        $this->generoModelo = 'masculino';
        $this->baseController   = new BaseController($this->modeloSingular, $this->modeloPlural, $this->generoModelo);
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @param App\Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function index(ClienteDomiciliosRequest $request, Cliente $cliente)
    {
        try {
            $modelos = $cliente->domicilios();

            $parametros = [
                'modelo' => $modelos,
                'eliminados' => $request->input('eliminados', false),
                'paginado' => [
                    'ordenarPor' => $request->input('ordenarPor', 'calle'),
                    'orden' => $request->input('orden', 'ASC'),
                ]
            ];

            $consulta = new Consulta();
            $domicilios = $consulta->ejecutarConsulta($parametros);

            return new ClienteDomicilioCollection($domicilios);
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->obtenerTodos($this->modeloPlural);
            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param  App\Http\Requests\Cliente\Domicilio\ClienteDomicilioRequest $request
     * @param App\Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function store(ClienteDomicilioRequest $request, Cliente $cliente)
    {
        $inputs = $request->only('localidad_id', 'calle', 'numero', 'aclaracion');
        $nombre = "El domicilio {$request->input('calle')} {$request->input('numero')}";

        try {
            $domicilio = new ClienteDomicilio($inputs);
            $cliente->domicilios()->save($domicilio);

            $domicilioGuardado = ClienteDomicilio::findOrFail($domicilio->id);
            $domicilioGuardado->localidad;

            $mensajeExito = new MensajeExito();
            $mensajeExito->guardar($nombre, $this->generoModelo);
            $respuesta = [$this->modeloSingular => $domicilioGuardado];
            return Respuesta::exito($respuesta, $mensajeExito, 201);
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->guardar($nombre, $this->generoModelo);
            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Display the specified resource.
     * @param  App\Cliente $cliente
     * @param  App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente, ClienteDomicilio $domicilio)
    {
        $domicilio->localidad;
        return $this->baseController->show($domicilio);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Cliente\Domicilio\ClienteDomicilioRequest $request
     * @param  App\Cliente $cliente
     * @param  App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteDomicilioRequest $request, Cliente $cliente, ClienteDomicilio $domicilio)
    {
        $inputs = $request->only('localidad_id', 'calle', 'numero', 'aclaracion');
        $nombres = [
            "exito" => "El domicilio {$request->input('calle')} {$request->input('numero')}",
            "error" => "El domicilio {$domicilio->calle} {$domicilio->numero}"
        ];
        $parametros = [
            'inputs' => $inputs,
            'instancia' => $domicilio,
        ];
        return $this->baseController->update($parametros, $nombres);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Cliente $cliente
     * @param  App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente, ClienteDomicilio $domicilio)
    {
        $nombre  = "El domicilio {$domicilio->calle} {$domicilio->numero}";
        return $this->baseController->destroy($domicilio, $nombre);
    }

    /**
     * Restaurar el domicilio que ha sido eliminado
     *
     * @param  App\Cliente $cliente
     * @param  App\ClienteDomicilio  $domicilio
     * @return \Illuminate\Http\Response
     */
    public function restore(Cliente $cliente, ClienteDomicilio $domicilio)
    {
        $nombre  = "El domicilio {$domicilio->calle} {$domicilio->numero}";
        return $this->baseController->restore($domicilio, $nombre);
    }
}
