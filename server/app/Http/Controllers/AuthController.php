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

        $response = compact('token');

        return $this->SetRespuestaToken($token);
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
    public function renovarToken(Request $request)
    {
        return $this->SetRespuestaToken($this->guard()->refresh());
    }

    /**
     * Formateo del JSON que devuelve el token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function SetRespuestaToken($token)
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
