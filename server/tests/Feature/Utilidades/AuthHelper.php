<?php

namespace Tests\Feature\Utilidades;

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
}
