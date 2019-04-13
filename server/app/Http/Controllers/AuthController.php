<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UsersRequest\UserLoginRequest;

class AuthController extends Controller
{
    /**
     * LogOut usuario (Invalida el Token)
     * @param  App\Http\Requests\UsersRequest\UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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
        $mensaje = 'Session Cerrada';
        $status = 200;

        if (!$this->guard()->logout()) {
            $mensaje = 'Tuvimos un Problema';
            $status = 500;
        }

        return response()->json(compact('mensaje'), $status);
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
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60 //para convertir a segundos
        ]);
    }

    public function guard()
    {
        return Auth::guard();
    }
}
