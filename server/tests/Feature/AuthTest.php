<?php

namespace Tests\Feature;

use Exception;
use Tests\TestCase;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;

    /**
     * Debería autenticar al usuario si las credenciales son correctas
     */
    public function testLoginCredencialesCorrectas()
    {
        $this->seed('UsuariosDesarrolloSeeder');

        $respuesta = $this->json('POST', 'api/auth/cliente/login', [
            'email' => 'applab@dev.com',
            'password' => '12345678'
        ]);

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure([
                'autenticacion' => ['token', 'tipoToken', 'fechaExpiracion']
            ])
            ->assertJson(['autenticacion' => ['tipoToken' => 'bearer']]);
    }

    /**
     * Debería devolver un error si las credenciales del usuario son incorrectas
     */
    public function testLoginCredencialesInvalidas()
    {
        $respuesta = $this->json('POST', 'api/auth/cliente/login', [
            'email' => 'no-existe@mail.com',
            'password' => '12345678'
        ]);

        $respuesta
            ->assertStatus(401)
            ->assertJsonStructure(['mensaje'])
            ->assertJson([
                'mensaje' => [
                    'codigo' => 'CREDENCIALES_INVALIDAS'
                ]
            ]);
    }

    /**
     * Debería renovar el token
     */
    public function testRenovarToken()
    {
        $headers = $this->loguearseComo('cliente');
        $respuesta = $this->withHeaders($headers)->json('POST', 'api/auth/renovar');

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure([
                'autenticacion' => ['token', 'tipoToken', 'fechaExpiracion']
            ])
            ->assertJson(['autenticacion' => ['tipoToken' => 'bearer']]);
    }

    /**
     * Debería obtener los datos del usuario logueado
     */
    public function testUsuarioLogueado()
    {
        $headers = $this->loguearseComo('cliente');
        $respuesta = $this->withHeaders($headers)->json('GET', 'api/auth/usuario');

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure(['usuario'])
            ->assertJson([
                'usuario' => [
                    'email' => 'applab@dev.com'
                ]
            ]);
    }

    /**
     * Debería cerrar la sesión del usuario
     */
    public function testLogout()
    {
        $headers = $this->loguearseComo('cliente');
        $respuesta = $this->withHeaders($headers)->json('POST', 'api/auth/logout');

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure(['mensaje' => ['tipo', 'codigo', 'descripcion']])
            ->assertJson([
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'LOGOUT',
                    'descripcion' => "Sesión cerrada"
                ]
            ]);
    }

    /**
     * No debería cerrar la sesión si no hay un usuario autenticado
     */
    public function testNoLogout()
    {
        $respuesta = $this->json('POST', 'api/auth/logout');
        $respuesta->assertStatus(500);
    }
}
