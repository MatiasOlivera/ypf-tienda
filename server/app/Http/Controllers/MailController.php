<?php

namespace App\Http\Controllers;

use App\Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = [
            'modelo' => 'Mail',
            'campos' => ['id', 'id_cliente', 'mail', 'created_at', 'updated_at', 'deleted_at', ],
            'relaciones' => null,
            'buscar' => $request->input("buscar", null),
            'eliminados' => $request->input("eliminados", false),
            'paginado' => [
                'porPagina'   => $request->input("porPagina", 10),
                'ordenadoPor' => $request->input("ordenadoPor", 'mail'),
                'orden'       => $request->input("orden", true),
            ]
        ];

        //mensajes
        $mensajes = [
            'error' => [
                'descripcion' => 'Hemos tenido un error durante la consulta de datos, intente nuevamente',
                'codigo'      => 'MAIL_INDEX_CONTROLLER',
            ],
            'exito' => [
                'descripcion' => 'operacion exitosa',
                'codigo'      => 'MAIL_CATCH_INDEX_CONTROLLER',
            ]
        ];

        $BaseController   = new BaseController;
        return $BaseController->index($parametros, $mensajes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nombre  = $request->input('mail');
        $parametros = [
            'inputs' => [
                'id_cliente' => $request->input('cliente'),
                'mail'       => $request->input('mail'),
            ],
            'modelo' => 'Mail',
        ];

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'MAIL_STORE_CONTROLLER',
                'descripcion' => "{$nombre} se ha creado con exito",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar guardar a {$nombre}",
                'codigo' => 'CATCH_MAIL_STORE'
            ],
        ];

        $BaseController = new BaseController();
        return $BaseController->store($parametros, $mensaje);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function show(Mail $mail)
    {
        return $mail;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mail $mail)
    {
        $nombre  = $request->input('mail');
        $parametros = [
            'inputs' => [
                'id_cliente' => $request->input('cliente'),
                'mail' => $request->input('mail'),
            ],
            'modelo' => $mail,
        ];

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'MAIL_STORE_CONTROLLER',
                'descripcion' => "{$nombre} se ha modificado",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar modificar el Mail {$nombre}",
                'codigo' => 'CATCH_MAIL_UPDATE'
            ],
        ];

        $BaseController  = new BaseController();
        return $BaseController->update($parametros, $mensaje);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mail $mail)
    {
        $nombre  = $mail->mail;
        $BaseController  = new BaseController();

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'MAIL_DESTROY_CONTROLLER',
                'descripcion' => "{$nombre} ha sido eliminado",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar eliminar a {$nombre}",
                'codigo' => 'MAIL_DESTROY_CONTROLLER'
            ],
        ];

        return $BaseController->destroy($mail, $mensaje);
    }

    /**
     * Restaurar el usuario que ha sido eliminado
     *
     * @param  \App\Mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function restore(Mail $mail)
    {
        $nombre  = $mail->mail;
        $BaseController  = new BaseController();

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'MAIL_RESTORE_CONTROLLER',
                'descripcion' => "{$nombre} ha sido dado de alta",
            ],
            'error' => [
                'descripcion' => "Hubo un error al intentar dar de alta {$nombre}",
                'codigo' => 'MAIL_RESTORE_CONTROLLER'
            ],
        ];

        return $BaseController->restore($mail, $mensaje);
    }
}
