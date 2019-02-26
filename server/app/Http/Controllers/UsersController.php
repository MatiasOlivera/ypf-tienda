<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UsersRequest\UserCreateRequest;
use App\Http\Requests\UsersRequest\UserUpdateRequest;
use App\User;
use Hash;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $campos = ['id', 'name', 'email', 'created_at'];

        $porPag = $request->input("porPag");

        $Users = new User();
        $lista = $Users::select($campos)->paginate($porPag);

        $respuesta = $lista;

        return $respuesta;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $pass = $request->input('password');
        $hash = Hash::make($pass);

        try {

            $User = new User();
            $User->name = $name;
            $User->email = $email;
            $User->password = $hash;
            $User->save();

            $transaccion = true;
        } catch (\Exception $e) {
            $transaccion = false;
        }

        if ($transaccion) {
            $respuesta = ['mensaje' => "El Usuario {$name} se creado con exito"];
            $status = 201;
        } else {
            $respuesta = ['mensaje' => "Hubo un problema al intentar guardar a {$name}"];
            $status = 400;
        }

        return response()->json($respuesta, $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function Show($id)
    {
        $user = User::findOrfail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Http\Request  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $usuario)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $pass = $request->input('password');
        $hash = Hash::make($pass);

        try {

            $usuario->name = $name;
            $usuario->email = $email;
            $usuario->password = $hash;
            $usuario->save();

            $respuesta = ['mensaje' => "{ $name} ha sido actualizada"];
            $status = 200;
        } catch (\Exception $e) {

            $respuesta = ['mensaje' => "Hubo un problema al intentar actualizar a { $name}"];
            $status = 400;
        }

        return response()->json($respuesta, $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param App\User $User
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $User)
    {
        $nombre = $User->name;
        $eliminada = $User->delete();

        if ($eliminada) {
            $respuesta = ['mensaje' => "se elimino a {$nombre} con exito"];
            $status = 200;
        } else {
            $respuesta = ['mensaje' => "Hubo un problema al intentar eliminar a {$nombre}"];
            $status = 400;
        }

        return response()->json($respuesta, $status);
    }


    /**
     * Restaurar la persona Fisica que ha sido eliminada
     *
     * @param  \App\User  $User
     * @return \Illuminate\Http\Response
     */
    public function restore(User $User)
    {
        $nombre = $User->name;
        $restaurada = $User->restore();

        if ($restaurada) {
            $respuesta = ['mensaje' => "{$nombre} ha sido dada de alta"];
            $status = 200;
        } else {
            $respuesta = ['mensaje' => "Hubo un problema al intentar dar de alta a {$nombre}"];
            $status = 400;
        }

        return response()->json($respuesta, $status);
    }
}
