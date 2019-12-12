<?php

namespace App\Http\Controllers;

use App\ClienteUsuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\UsersRequest\ClienteLoginRequest;
use App\Http\Requests\Autenticacion\EmpleadoLoginRequest;
use App\Auxiliares\{Respuesta, MensajeExito, MensajeError};

class AuthController extends Controller
{
    public function clienteLogin(ClienteLoginRequest $request)
    {
        $credenciales = $request->only('email', 'password');

        try {
            $token = Auth::guard('cliente')
                ->claims(['tipo' => 'cliente'])
                ->attempt($credenciales);

            if (!$token) {
                $mensajeError = new MensajeError('Credenciales inválidas', 'CREDENCIALES_INVALIDAS');
                return Respuesta::error($mensajeError, 401);
            }

            return $this->setRespuestaToken($token);
        } catch (JWTException $e) {
            $mensajeError = new MensajeError('Hubo un problema al intentar iniciar la sesión', 'NO_AUTENTICADO');
            return Respuesta::error($mensajeError, 500);
        }
    }

    public function empleadoLogin(EmpleadoLoginRequest $request)
    {
        try {
            $credenciales = $request->only('documento', 'password');
            $token = Auth::guard('empleado')
                ->claims(['tipo' => 'empleado'])
                ->attempt($credenciales);

            if (!$token) {
                $mensajeError = new MensajeError('Credenciales inválidas', 'CREDENCIALES_INVALIDAS');
                return Respuesta::error($mensajeError, 401);
            }

            return $this->setRespuestaToken($token);
        } catch (JWTException $e) {
            $mensajeError = new MensajeError('Hubo un problema al intentar iniciar la sesión', 'NO_AUTENTICADO');
            return Respuesta::error($mensajeError, 500);
        }
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

            $mensaje = new MensajeExito('Sesión cerrada', 'LOGOUT');
            return Respuesta::exito([], $mensaje, 200);
        } catch (\Throwable $th) {
            $mensaje = new MensajeError('Hubo un problema al intentar cerrar la sesión', 'NO_LOGOUT');
            return Respuesta::error($mensaje, 500);
        }
    }

    /**
     * obtiene los datos del usuario logueado
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function usuario(Request $request)
    {
        $usuario = $request->user();
        return Respuesta::exito(['usuario' => $usuario], null, 200);
    }

    /**
     * Renueva el token si ya no expiro
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function renovar(Request $request)
    {
        try {
            $token = Auth::guard()->refresh();
            return $this->setRespuestaToken($token);
        } catch (JWTException $e) {
            $mensaje = new MensajeError('Token inválido', 'NO_VALIDO');
            return Respuesta::error($mensaje, 401);
        }
    }

    /**
     * Formateo del JSON que devuelve el token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function setRespuestaToken($token)
    {
        $ttl = Auth::guard()->factory()->getTTL();

        $fechaActual = Carbon::now();
        $fechaExpiracion = $fechaActual->addMinutes($ttl);
        $fechaExpiracionJSON = $fechaExpiracion->toIso8601String();

        $autenticacion = [
            'token' => $token,
            'tipoToken' => 'bearer',
            'fechaExpiracion' => $fechaExpiracionJSON
        ];

        return Respuesta::exito(['autenticacion' => $autenticacion], null, 200);
    }
}
