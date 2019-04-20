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
    private $controladorBase;

    public function __construct()
    {
        $this->controladorBase = new BaseController('usuario', 'usuarios');
    }

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

        return $this->controladorBase->index($parametros, 'los usuarios');
    }

    /**
     * Guarda un nuevo usuario en la BD.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $input   = $request->only('name', 'email', 'password');
        $nombre   = $request->input('name');
        $input['password'] = Hash::make($request->input('password'));

        //parametros
        $parametros = [
            'input' => $input,
            'modelo' => 'User',
        ];


        $nombres = [
            'exito' => "{$nombre}",
            'error' => "{$nombre}"
        ];

        return $this->controladorBase->store($parametros, $nombres);
    }

    /**
     * Muestra un usuario especifico.
     *
     * @param App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->controladorBase->show($user);
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
        $input  = $request->only('name', 'email');
        $nombre  = $request->input('name');
        $parametros = [
            'input' => $input,
            'instancia' => $user,
        ];

        $nombres = [
            'exito' => "{$nombre}",
            'error' => "{$nombre}",
        ];

        return $this->controladorBase->update($parametros, $nombres);
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

        $nombres = [
            'exito' => "{$nombre}",
            'error' => "{$nombre}",
        ];

        return $this->controladorBase->destroy($user, $nombres);
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

        $nombres = [
            'exito' => "{$nombre}",
            'error' => "{$nombre}",
        ];

        return $this->controladorBase->restore($user, $nombres);
    }
}
