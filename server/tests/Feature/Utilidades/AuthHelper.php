<?php

namespace Tests\Feature\Utilidades;

use App\Empleado;
use App\ClienteUsuario;

trait AuthHelper
{
    protected function loguearseComo(string $usuario = 'cliente'): array
    {
        $this->seed('UsuariosDesarrolloSeeder');

        $usuarios = [
            'cliente' => [
                'email' => 'applab@dev.com',
                'password' => '12345678'
            ]
        ];

        if (!array_key_exists($usuario, $usuarios)) {
            throw new \Exception("El nivel no es válido");
        }

        $credenciales = $usuarios[$usuario];

        $token = "";

        if ($usuario === 'cliente') {
            $respuesta = $this->json('POST', 'api/auth/cliente/login', [
                'email' => $credenciales['email'],
                'password' => $credenciales['password']
            ]);

            $token = $respuesta->original['autenticacion']['token'];
        }

        return ['authorization' => "bearer $token"];
    }

    protected function loguearseComoCliente(): array
    {
        $clienteUsuario = factory(ClienteUsuario::class)->create();

        $respuesta = $this->json('POST', 'api/auth/cliente/login', [
            'email' => $clienteUsuario->email,
            'password' => '12345678'
        ]);

        $token = $respuesta->getOriginalContent()['autenticacion']['token'];

        $cabeceras = ['authorization' => "bearer $token"];

        return [
            'usuario' => $clienteUsuario,
            'cabeceras' => $cabeceras
        ];
    }

    protected function loguearseComoEmpleado(): array
    {
        $empleado = factory(Empleado::class)->create();

        $respuesta = $this->json('POST', 'api/auth/empleado/login', [
            'documento' => $empleado->documento,
            'password' => '12345678'
        ]);

        $token = $respuesta->getOriginalContent()['autenticacion']['token'];

        $cabeceras = ['authorization' => "bearer $token"];

        return [
            'usuario' => $empleado,
            'cabeceras' => $cabeceras
        ];
    }
}
