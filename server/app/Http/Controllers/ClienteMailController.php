<?php

namespace App\Http\Controllers;

use App\{ Cliente, ClienteMail };
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Cliente\Mail\ClienteMailRequest;
use App\Auxiliares\{ Respuesta, MensajeExito, MensajeError };

class ClienteMailController extends Controller
{
    protected $baseController;
    protected $modeloSingular;
    protected $modeloPlural;

    public function __construct()
    {
        $this->modeloPlural     = 'emails';
        $this->modeloSingular   = 'email';
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
            $porPagina      = $request->only('porPagina');
            $mails          = $cliente->mails()->paginate($porPagina);
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

            $mensajeExito = new MensajeExito();
            $mensajeExito->guardar($nombre);

            $respuesta      = [$this->modeloSingular => $mail];
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
     * @param  \App\ClienteMail  $mail
     * @return \Illuminate\Http\Response
     */
    public function show(ClienteMail $mail)
    {
        return $this->baseController->show($mail);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Cliente\Mail\ClienteMailRequest  $request
     * @param  \App\ClienteMail  $mail
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteMailRequest $request, ClienteMail $mail)
    {
        $email = $request->input('mail');
        $nombres = [
            "exito" => "El email {$email}",
            "error" => "El email {$mail->mail}"
        ];
        $parametros = [
            'inputs' => $email,
            'modelo' => $mail,
        ];
        return $this->baseController->update($parametros, $nombres);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ClienteMail  $mail
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClienteMail $mail)
    {
        $email = $mail->mail;
        $nombre = "El email {$email}";
        return $this->baseController->destroy($mail, $nombre);
    }

    /**
     * Restaurar el Mail que ha sido eliminado
     *
     * @param  \App\ClienteMail  $mail
     * @return \Illuminate\Http\Response
     */
    public function restore(ClienteMail $mail)
    {
        $email  = $mail->mail;
        $nombre = "El email {$email}";
        return $this->baseController->restore($mail, $nombre);
    }
}
