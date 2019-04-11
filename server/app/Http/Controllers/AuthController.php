<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Hash;
use tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Carbon\Carbon;

//Users Request
use App\Http\Requests\UsersRequest\UserLoginRequest;

class AuthController extends Controller
{

    public function login(UserLoginRequest $request)
    {
        $credenciales = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credenciales)) {
                $mensaje['error'] = 'Credenciales invalidas';
                $status = 401;
                return response()->json($mensaje, $status);
            }
        } catch (JWTException $e) {
            $mensaje['error'] = 'no pudimos autenticarlo';
            $status = 500;
            return response()->json($mensaje, $status);
        }

        return $this->setRespuestaToken($token);
    }

    /**
     * LogOut usuario (Invalida el Token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth()->logout();

            $mensaje = 'Sesión cerrada';
            $status = 200;

            return response()->json(compact('mensaje'), $status);
        } catch (\Throwable $th) {
            $mensaje = 'Tuvimos un problema';
            $status = 500;

            return response()->json(compact('mensaje'), $status);
        }
    }

    /**
     * obtiene los datos del usuario logueado
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function usuario(Request $request)
    {
        return $request->user();
    }

    /**
     * Renueva el token si ya no expiro
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function renovar(Request $request)
    {
        try {
            $token = $this->guard()->refresh();
            return $this->setRespuestaToken($token);
        } catch (JWTException $e) {
            $mensaje = 'Token Invalido';
            $status = 401;
            return response()->json(compact('mensaje'), $status);
        }
    }

    /**
     * Formateo del JSON que devuelve el token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function setRespuestaToken($token)
    {
        $ttl = $this->guard()->factory()->getTTL();

        $fechaActual = Carbon::now();
        $fechaExpiracion = $fechaActual->addMinutes($ttl);
        $fechaExpiracionJSON = $fechaExpiracion->toIso8601String();

        return response()->json([
            'token' => $token,
            'tipoToken' => 'bearer',
            'fechaExpiracion' => $fechaExpiracionJSON
        ]);
    }

    public function guard()
    {
        return Auth::guard();
    }
}
