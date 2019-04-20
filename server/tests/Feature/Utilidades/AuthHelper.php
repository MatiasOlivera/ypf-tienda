<?php

namespace Tests\Feature\Utilidades;

trait AuthHelper
{
    protected function loguearseComo(string $usuario = 'defecto'): array
    {
        $this->seed('UsuariosDesarrolloSeeder');

        $usuarios = [
            'defecto' => [
                'email' => 'applab@dev.com',
                'password' => '12345678'
            ]
        ];

        if (!array_key_exists($usuario, $usuarios)) {
            throw new Exception("El nivel no es válido");
        }

        $credenciales = $usuarios[$usuario];

        $respuesta = $this->json('POST', 'api/auth/login', [
            'email' => $credenciales['email'],
            'password' => $credenciales['password']
        ]);

        $token = $respuesta->original['autenticacion']['token'];

        return ['authorization' => "bearer $token"];
    }
}
