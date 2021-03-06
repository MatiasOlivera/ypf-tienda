<?php

namespace App\Http\Controllers;

use App\Auxiliares\Consulta;
use Illuminate\Http\Request;
use App\{ Cliente, ClienteMail };
use App\Http\Controllers\BaseController;
use App\Http\Resources\ClienteMailCollection;
use App\Http\Requests\Cliente\Mail\ClienteMailRequest;
use App\Http\Requests\Cliente\Mail\ClienteMailsRequest;
use App\Auxiliares\{ Respuesta, MensajeExito, MensajeError };

class ClienteMailController extends Controller
{
    protected $baseController;
    protected $modeloSingular;
    protected $modeloPlural;
    protected $generoModelo;

    public function __construct()
    {
        $this->modeloPlural     = 'emails';
        $this->modeloSingular   = 'email';
        $this->generoModelo = 'masculino';
        $this->baseController   = new BaseController($this->modeloSingular, $this->modeloPlural, $this->generoModelo);
    }

    /**
     * Display a listing of the resource.
     * @param  App\Cliente $cliente
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(ClienteMailsRequest $request, Cliente $cliente)
    {
        try {
            $modelos = $cliente->mails();

            $parametros = [
                'modelo' => $modelos,
                'eliminados' => $request->input('eliminados', false),
                'paginado' => [
                    'ordenarPor' => 'mail',
                    'orden' => $request->input('orden', 'ASC'),
                ]
            ];

            $consulta = new Consulta();
            $emails = $consulta->ejecutarConsulta($parametros);

            return new ClienteMailCollection($emails);
        } catch (\Throwable $th) {
            $mensajeError   = new MensajeError();
            $mensajeError->obtenerTodos($this->modeloPlural);
            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Cliente\Mail\ClienteMailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClienteMailRequest $request, Cliente $cliente)
    {
        $email  = $request->input('mail');
        $nombre = "El email {$email}";

        try {
            $inputs = ['mail' => $email];
            $mail   = new ClienteMail($inputs);
            $mail   = $cliente->mails()->save($mail);

            $emailGuardado = ClienteMail::findOrFail($mail->id);

            $mensajeExito = new MensajeExito();
            $mensajeExito->guardar($nombre, $this->generoModelo);

            $respuesta      = [$this->modeloSingular => $emailGuardado];
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
     * @param App\Cliente $cliente
     * @param  \App\ClienteMail  $mail
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente, ClienteMail $mail)
    {
        return $this->baseController->show($mail);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Cliente\Mail\ClienteMailRequest  $request
     * @param App\Cliente $cliente
     * @param  \App\ClienteMail  $mail
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteMailRequest $request, Cliente $cliente, ClienteMail $mail)
    {
        $inputs = $request->only('mail');
        $email = $request->input('mail');

        $nombres = [
            "exito" => "El email {$email}",
            "error" => "El email {$mail->mail}"
        ];
        $parametros = [
            'inputs' => $inputs,
            'instancia' => $mail,
        ];
        return $this->baseController->update($parametros, $nombres);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param App\Cliente $cliente
     * @param  \App\ClienteMail  $mail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente, ClienteMail $mail)
    {
        $email = $mail->mail;
        $nombre = "El email {$email}";
        return $this->baseController->destroy($mail, $nombre);
    }

    /**
     * Restaurar el Mail que ha sido eliminado
     *
     * @param App\Cliente $cliente
     * @param  \App\ClienteMail  $mail
     * @return \Illuminate\Http\Response
     */
    public function restore(Cliente $cliente, ClienteMail $mail)
    {
        $email  = $mail->mail;
        $nombre = "El email {$email}";
        return $this->baseController->restore($mail, $nombre);
    }
}
