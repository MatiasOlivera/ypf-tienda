<?php

namespace App\Http\Controllers;

use App\{ Cliente, ClienteMail };
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Cliente\Mail\ClienteMailRequest;
use App\Auxiliares\{ Respuesta, MensajeExito, MensajeError };

class ClienteMailController extends Controller
{
    protected $BaseController;
    protected $modeloSingular;
    protected $modeloPlural;

    public function __Construct()
    {
        $this->modeloPlural     = 'emails';
        $this->modeloSingular   = 'email';
        $this->BaseController   = new BaseController($this->modeloSingular, $this->modeloPlural);
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
            $porPagina      = ($request->only('porPagina') > 0) ? $request->only('porPagina') : 5;
            $mails          = $cliente->mails()->paginate($porPagina);
            $respuesta      = [$this->modeloPlural => $mails];
            return Respuesta::exito($respuesta, null, 200);
        } catch (\Throwable $th) {
            $mensajeError   = new MensajeError();
            $mensajeError->obtenerTodos("E-Mail's");
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
        try {
            $inputs = ['mail' => $email];
            $mail   = new ClienteMail($inputs);
            $mail   = $cliente->mails()->save($mail);

            $mensajeExito = new MensajeExito();
            $mensajeExito->guardar($email);

            $respuesta      = [$this->modeloSingular => $mail];
            return Respuesta::exito($respuesta, $mensajeExito, 200);
        } catch (\Throwable $th) {

            $mensajeError   = new MensajeError();
            $mensajeError->guardar($this->modeloSingular);

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
        return $this->BaseController->show($mail);
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
        $email      = $request->input('mail');
        $parametros = [
            'inputs' => $email,
            'modelo' => $mail,
        ];
        return $this->BaseController->update($parametros, $email);
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
        return $this->BaseController->destroy($mail, $email);
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
        return $this->BaseController->restore($mail, $email);
    }
}
