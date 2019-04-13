<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\ClienteMail;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Cliente\Mail\ClienteMailRequest;

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
        $mensaje = [
            'error' => [
                'descripcion' => 'Hemos tenido un error durante la consulta de datos, intente nuevamente',
                'codigo'      => 'MAIL_INDEX_CONTROLLER',
            ],
        ];
        try {
            $mail = $cliente->mails;
            return response()->json(['datos' => $mail], 200);
        } catch (\Throwable $th) {
            $respuesta = [
                'datos'     => null,
                'mensajes'  => [
                    'tipo'      => 'error',
                    'codigo'    => $mensaje['error']['codigo'],
                    'mensaje'   => $mensaje['error']['descripcion'],
                ],
            ];
            return response()->json($respuesta, 400);
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

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo'        => 'MAIL_STORE_CONTROLLER',
                'descripcion'   => "{$email} se ha creado con exito",
            ],
            'error' => [
                'descripcion'   => "Hubo un error al intentar guardar {$email}",
                'codigo'        => 'CATCH_MAIL_STORE'
            ],
        ];
        try {
            $inputs = ['mail' => $email,];
            $mail = new ClienteMail($inputs);
            $cliente->mails()->save($mail);
            $respuesta = [
                'datos'     => $mail,
                'mensajes'  => [
                    'tipo'      => 'exito',
                    'codigo'    => $mensaje['exito']['codigo'],
                    'mensaje'   => $mensaje['exito']['descripcion'],
                ],
            ];

            return response()->json($respuesta, 200);
        } catch (\Throwable $th) {
            $respuesta = [
                'datos'     => null,
                'mensajes'  => [
                    'tipo'      => 'error',
                    'codigo'    => $mensaje['error']['codigo'],
                    'mensaje'   => $mensaje['error']['descripcion'],
                ],
            ];
            return response()->json($respuesta, 400);
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
     * @param  App\Http\Requests\Cliente\Mail\ClienteMailRequest  $request
     * @param  \App\ClienteMail  $mail
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteMailRequest $request, ClienteMail $mail)
    {
        //mensajes
        $emailNew  = $request->input('mail');
        $emailOLD  = $mail->mail;
        $mensaje = [
            'exito' => [
                'codigo'        => 'MAIL_STORE_CONTROLLER',
                'descripcion'   => "{$emailNew} se ha modificado",
            ],
            'error' => [
                'descripcion'   => "Hubo un error al intentar modificar el Mail {$emailOLD}",
                'codigo'        => 'CATCH_MAIL_UPDATE'
            ],
        ];

        try {
            $inputs = ['mail' => $email,];
            $mail->fill($inputs);
            $mail->save();
            $respuesta = [
                'datos'     => $mail,
                'mensajes'  => [
                    'tipo'      => 'exito',
                    'codigo'    => $mensaje['exito']['codigo'],
                    'mensaje'   => $mensaje['exito']['descripcion'],
                ],
            ];

            return response()->json($respuesta, 200);
        } catch (\Throwable $th) {
            $respuesta = [
                'datos'     => null,
                'mensajes'  => [
                    'tipo'      => 'error',
                    'codigo'    => $mensaje['error']['codigo'],
                    'mensaje'   => $mensaje['error']['descripcion'],
                ],
            ];
            return response()->json($respuesta, 400);
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
        //mensajes
        $email  = $mail->mail;
        $mensaje = [
            'exito' => [
                'codigo'        => 'MAIL_DESTROY_CONTROLLER',
                'descripcion'   => "{$email} ha sido eliminado",
            ],
            'error' => [
                'descripcion'   => "Hubo un error al intentar eliminar a {$email}",
                'codigo'        => 'CATCH_MAIL_DESTROY'
            ],
        ];

        $BaseController  = new BaseController();
        return $BaseController->destroy($mail, $mensaje);
    }

    /**
     * Restaurar el Mail que ha sido eliminado
     *
     * @param  \App\ClienteMail  $mail
     * @return \Illuminate\Http\Response
     */
    public function restore(ClienteMail $mail)
    {
        //mensajes
        $email  = $mail->mail;
        $mensaje = [
            'exito' => [
                'codigo'        => 'MAIL_RESTORE_CONTROLLER',
                'descripcion'   => "{$email} ha sido dado de alta",
            ],
            'error' => [
                'descripcion'   => "Hubo un error al intentar dar de alta {$email}",
                'codigo'        => 'MAIL_RESTORE_CONTROLLER'
            ],
        ];

        $BaseController  = new BaseController();
        return $BaseController->restore($mail, $mensaje);
    }
}
