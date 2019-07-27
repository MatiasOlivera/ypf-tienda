<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Cliente;
use Tests\TestCase;
use App\ClienteMail;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class ClienteEmailControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EstructuraJsonHelper;

    private $estructuraEmail = [
        'email' => [
            'id',
            'mail',
            'cliente_id',
            'created_at',
            'updated_at',
            'deleted_at'
        ]
    ];

    private function getEstructuraEmails()
    {
        return array_merge(['emails'], $this->estructuraPaginacion);
    }

    private function getEstructuraEmail()
    {
        return array_merge($this->estructuraEmail, $this->estructuraMensaje);
    }

    private function crearEmail()
    {
        return factory(ClienteMail::class, 1)->create()->toArray()[0];
    }

    /**
     * No debería obtener ningún email
     */
    public function testNoDeberiaObtenerNingunEmail()
    {
        $cliente = factory(Cliente::class, 1)->create()->toArray()[0];
        $id = $cliente['id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$id/emails");

        $estructura = $this->getEstructuraEmails();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertExactJson(['emails' => []]);
    }

    /**
     * Debería obtener emails
     */
    public function testDeberiaObtenerEmails()
    {
        factory(ClienteMail::class, 2)->create();

        $cliente = Cliente::inRandomOrder()->first();
        $id = $cliente->id;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$id/emails");

        $estructura = $this->getEstructuraEmails();
        $emails = $cliente->mails()->get()->toArray();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['emails' => $emails]);
    }

    /**
     * Debería crear un email
     */
    public function testDeberiaCrearUnEmail()
    {
        $email = factory(ClienteMail::class, 1)->make()->toArray()[0];
        $id = $email['cliente_id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/clientes/$id/emails", $email);

        $estructura = $this->getEstructuraEmail();
        unset($email['id']);

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'email' => $email,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => "El email {$email['mail']} ha sido creado"
                ]
            ]);
    }

    /**
     * Debería obtener un email
     */
    public function testDeberiaObtenerUnEmail()
    {
        $email = $this->crearEmail();
        $clienteId = $email['cliente_id'];
        $id = $email['id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$clienteId/emails/$id");

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($this->estructuraEmail)
            ->assertJson([
                'email' => $email
            ]);
    }

    /**
     * Debería editar un email
     */
    public function testDeberiaEditarUnEmail()
    {
        $email = $this->crearEmail();
        $clienteId = $email['cliente_id'];
        $id = $email['id'];

        $emailModificado = array_merge($email, ['mail' => 'nuevo@gmail.com']);

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('PUT', "api/clientes/$clienteId/emails/$id", $emailModificado);

        unset($emailModificado['updated_at']);
        $estructura = $this->getEstructuraEmail();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'email' => $emailModificado,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => "El email nuevo@gmail.com ha sido modificado"
                ]
            ]);
    }

    /**
     * Debería eliminar un email
     */
    public function testDeberiaEliminarUnEmail()
    {
        $email = $this->crearEmail();
        $clienteId = $email['cliente_id'];
        $id = $email['id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/clientes/$clienteId/emails/$id");

        unset($email['updated_at']);
        $estructura = $this->getEstructuraEmail();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'email' => $email,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ELIMINADO',
                    'descripcion' => "El email {$email['mail']} ha sido eliminado"
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['email']['deleted_at'];
        $this->assertNotNull($deletedAt);
    }

    /**
     * Debería restaurar un email
     */
    public function testDeberiaRestaurarUnEmail()
    {
        $email = $this->crearEmail();
        $clienteId = $email['cliente_id'];
        $id = $email['id'];

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/clientes/$clienteId/emails/$id");

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/clientes/$clienteId/emails/$id/restaurar");

        unset($email['updated_at']);
        $estructura = $this->getEstructuraEmail();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'email' => $email,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'RESTAURADO',
                    'descripcion' => "El email {$email['mail']} ha sido dado de alta"
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['email']['deleted_at'];
        $this->assertNull($deletedAt);
    }
}
