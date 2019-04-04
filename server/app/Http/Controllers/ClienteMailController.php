<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\ClienteMail;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class ClienteMailController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  App\Cliente $cliente
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Cliente $cliente)
    {
        $mensajes = [
            'error' => [
                'descripcion' => 'Hemos tenido un error durante la consulta de datos, intente nuevamente',
                'codigo'      => 'MAIL_INDEX_CONTROLLER',
            ],
        ];
        try {
            $mail = $cliente->mails;
            return response()->json(['datos' => $mail], 200);
        } catch (\Throwable $th) {
            return response()->json($mensajes['error'], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Cliente $cliente)
    {
        $nombre  = $request->input('mail');

        //mensajes
        $mensajes = [
            'exito' => [
                'codigo'        => 'MAIL_STORE_CONTROLLER',
                'descripcion'   => "{$nombre} se ha creado con exito",
            ],
            'error' => [
                'descripcion'   => "Hubo un error al intentar guardar a {$nombre}",
                'codigo'        => 'CATCH_MAIL_STORE'
            ],
        ];
        try {
            $mail = new ClienteMail(['mail' => $request->input('mail'),]);
            $cliente->mails()->save($mail);
            $mails = $cliente->mails;

            return response()->json([
                'datos'     => $mails,
                'mensajes'  => $mensajes['exito'],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($mensajes['error'], '400');
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
        return $mail;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClienteMail  $mail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente, ClienteMail $mail)
    {
        //mensajes
        $nombre  = $request->input('mail');
        $mensajes = [
            'exito' => [
                'codigo'        => 'MAIL_STORE_CONTROLLER',
                'descripcion'   => "{$nombre} se ha modificado",
            ],
            'error' => [
                'descripcion'   => "Hubo un error al intentar modificar el Mail {$nombre}",
                'codigo'        => 'CATCH_MAIL_UPDATE'
            ],
        ];

        try {
            $inputs = ['mail' => $request->input('mail'),];
            $mail->fill($inputs);
            $save = $mail->save();

            if ($save) {
                $mails = $cliente->mails;
                return response()->json([
                    'datos'     => $mails,
                    'mensajes'  => $mensajes['exito'],
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json($mensajes['error'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ClienteMail  $mail
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClienteMail $mail)
    {
        $nombre  = $mail->mail;
        $BaseController  = new BaseController();

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo'        => 'MAIL_DESTROY_CONTROLLER',
                'descripcion'   => "{$nombre} ha sido eliminado",
            ],
            'error' => [
                'descripcion'   => "Hubo un error al intentar eliminar a {$nombre}",
                'codigo'        => 'CATCH_MAIL_DESTROY'
            ],
        ];

        return $BaseController->destroy($mail, $mensaje);
    }

    /**
     * Restaurar el usuario que ha sido eliminado
     *
     * @param  \App\ClienteMail  $mail
     * @return \Illuminate\Http\Response
     */
    public function restore(ClienteMail $mail)
    {
        $nombre  = $mail->mail;
        $BaseController  = new BaseController();

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo'        => 'MAIL_RESTORE_CONTROLLER',
                'descripcion'   => "{$nombre} ha sido dado de alta",
            ],
            'error' => [
                'descripcion'   => "Hubo un error al intentar dar de alta {$nombre}",
                'codigo'        => 'MAIL_RESTORE_CONTROLLER'
            ],
        ];

        return $BaseController->restore($mail, $mensaje);
    }
}
