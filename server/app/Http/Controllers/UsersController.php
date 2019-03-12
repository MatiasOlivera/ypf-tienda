<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UsersRequest\UserCreateRequest;
use App\Http\Requests\UsersRequest\UserUpdateRequest;
use App\User;
use Hash;
use App\Http\Controllers\BaseController;

class UsersController extends Controller
{

    /**
     * Muestra una lista de usuarios.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = [
            'modelo' => 'User',
            'campos' => ['id', 'name', 'email', 'email_verified_at', 'id_cliente', 'created_at', 'updated_at', 'deleted_at', ],
            'relaciones' => null,
            'buscar' => $request->input("buscar", null),
            'eliminados' => $request->input("eliminados", false),
            'paginado' => [
                'porPagina'   => $request->input("porPagina", 10),
                'ordenadoPor' => $request->input("ordenadoPor", 'name'),
                'orden'       => $request->input("orden", true),
            ]
        ];

        $mensajes = [
            'error' => [
                'descripcion' => 'Hemos tenido un error durante la consulta de datos, intente nuevamente',
                'codigo'      => 'USER_INDEX_CONTROLLER',
            ],
            'exito' => [
                'descripcion' => 'operacion exitosa',
                'codigo'      => 'USER_CATCH_INDEX_CONTROLLER',
            ]
        ];

        $metodo   = new BaseController;
        return $metodo->index($parametros, $mensajes);
    }

    /**
     * Guarda un nuevo usuario en la BD.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $metodo   = new BaseController();
        $inputs   = $request->only('name', 'email', 'password');
        $nombre   = $request->input('name');
        $inputs['password'] = Hash::make($request->input('password'));

        //parametros
        $parametros = [
            'inputs' => $inputs,
            'modelo' => 'User',
        ];

        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'USER_STORE_CONTROLLER',
                'descripcion' => "Usuario {$nombre} Creado con exito",
            ],
            'error' => [
                'descripcion' => "No pudimos guardar el Usuario {$nombre}",
                'codigo' => 'CATCH_USER_STORE'
            ],
        ];

        return $metodo->store($parametros, $mensaje);
    }

    /**
     * Muestra un usuario especifico.
     *
     * @param App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Actualizar el usuario especifico en la BD.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Http\Request  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $inputs  = $request->only('name', 'email');
        $nombre  = $request->input('name');
        $parametros = [
            'inputs' => $inputs,
            'modelo' => $user,
        ];
        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'USER_STORE_CONTROLLER',
                'descripcion' => "El usuario {$nombre} ha sido actualizado",
            ],
            'error' => [
                'descripcion' => "Hubo un problema al intentar actualizar el Usuario  {$nombre}",
                'codigo' => 'CATCH_USER_UPDATE'
            ],
        ];

        $metodo  = new BaseController();
        return $metodo->update($parametros, $mensaje);
    }

    /**
     * elimina el usuario especifico de la BD
     *
     * @param App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $nombre  = $user->name;
        $metodo  = new BaseController();
        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'USER_DESTROY_CONTROLLER',
                'descripcion' => "se elimino a {$nombre} con exito",
            ],
            'error' => [
                'descripcion' => "Hubo un problema al intentar eliminar al Usuario {$nombre}",
                'codigo' => 'USER_DESTROY_CONTROLLER'
            ],
        ];

        return $metodo->destroy($user, $mensaje);
    }

    /**
     * Restaurar el usuario que ha sido eliminado
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function restore(User $user)
    {
        $nombre  = $user->name;
        $metodo  = new BaseController();
        //mensajes
        $mensaje = [
            'exito' => [
                'codigo' => 'USER_RESTORE_CONTROLLER',
                'descripcion' => "El Usuario {$nombre} ha sido dado de alta",
            ],
            'error' => [
                'descripcion' => "Hubo un problema al intentar dar de alta al Usuario {$nombre}",
                'codigo' => 'USER_RESTORE_CONTROLLER'
            ],
        ];

        return $metodo->restore($user, $mensaje);
    }
}
